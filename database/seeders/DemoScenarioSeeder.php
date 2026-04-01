<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\CaseModel;
use App\Models\Client;
use App\Models\CourtSession;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * DemoScenarioSeeder
 *
 * Creates realistic day-of scenarios for the demo account (demo@qistas.test):
 *  - Urgent cases with sessions TODAY
 *  - Court sessions scheduled for today and tomorrow
 *  - Overdue tasks (past due dates, still pending/in_progress)
 *  - Unread session-reminder notifications
 *  - Recent audit activity to populate dashboard widgets
 */
class DemoScenarioSeeder extends Seeder
{
    public function run(): void
    {
        // ── Locate the demo firm ─────────────────────────────────────────────
        $demoUser = User::withoutGlobalScopes()->where('email', 'demo@qistas.test')->firstOrFail();
        $firmId   = $demoUser->law_firm_id;

        // All users that belong to the demo firm
        $firmUsers = User::withoutGlobalScopes()
            ->where('law_firm_id', $firmId)
            ->get();

        $firmOwner = $firmUsers->firstWhere('role', 'owner') ?? $demoUser;
        $firmLawyers = $firmUsers->where('role', 'lawyer')->values();

        // Grab (or create) some clients for this firm
        $clients = Client::withoutGlobalScopes()
            ->where('law_firm_id', $firmId)
            ->take(10)
            ->get();

        if ($clients->isEmpty()) {
            $fallbackClients = [
                ['name' => 'شركة البيان للتجهيزات', 'phone' => '021991122', 'email' => 'legal@albayan.dz', 'address' => 'الجزائر العاصمة', 'id_number' => 'RC-16A-11002'],
                ['name' => 'رياض بن ناصر', 'phone' => '0667010203', 'email' => 'riad.bennacer@example.dz', 'address' => 'البليدة', 'id_number' => 'ID-DZ-440021'],
                ['name' => 'مؤسسة الواحة للخدمات', 'phone' => '035881177', 'email' => 'admin@oasis-services.dz', 'address' => 'قسنطينة', 'id_number' => 'RC-25C-55311'],
                ['name' => 'سلمى زروقي', 'phone' => '0770112233', 'email' => 'salma.zrouki@example.dz', 'address' => 'عنابة', 'id_number' => 'ID-DZ-441019'],
                ['name' => 'شركة النخبة للمقاولات', 'phone' => '041776655', 'email' => 'contracts@nokhba-build.dz', 'address' => 'وهران', 'id_number' => 'RC-31W-77812'],
                ['name' => 'كمال بوزيد', 'phone' => '0558123456', 'email' => 'kamal.bouzid@example.dz', 'address' => 'تيبازة', 'id_number' => 'ID-DZ-770981'],
            ];

            foreach ($fallbackClients as $fi => $fallbackClient) {
                $client = Client::withoutGlobalScopes()->create([
                    'law_firm_id' => $firmId,
                    'name' => $fallbackClient['name'],
                    'phone' => $fallbackClient['phone'],
                    'email' => $fallbackClient['email'],
                    'address' => $fallbackClient['address'],
                    'id_number' => $fallbackClient['id_number'],
                    'notes' => 'عميل ضمن بيانات العرض التجريبي.',
                ]);

                // Attach a lawyer to each demo client
                if ($firmLawyers->isNotEmpty()) {
                    $client->lawyers()->sync([$firmLawyers[$fi % $firmLawyers->count()]->id]);
                }
            }

            $clients = Client::withoutGlobalScopes()
                ->where('law_firm_id', $firmId)
                ->take(10)
                ->get();
        }

        $today    = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // ── 1. Urgent cases with a session TODAY ────────────────────────────
        $urgentCaseData = [
            ['title' => 'طعن بالنقض – قضية إرث عائلي', 'court' => 'المحكمة العليا',    'case_type' => 'عائلية',  'degree' => 'نقض'],
            ['title' => 'نزاع تجاري – عقد توريد مخالف', 'court' => 'محكمة الجزائر',    'case_type' => 'تجارية',  'degree' => 'ابتدائي'],
            ['title' => 'دعوى تعويض – حادث مروري مميت','court' => 'محكمة وهران',       'case_type' => 'مدنية',   'degree' => 'استئناف'],
        ];

        $urgentCases = collect();
        $urgentFees = [
            ['total' => 220000, 'paid' => 90000, 'monthsAgo' => 10],
            ['total' => 165000, 'paid' => 65000, 'monthsAgo' => 6],
            ['total' => 280000, 'paid' => 140000, 'monthsAgo' => 4],
        ];

        foreach ($urgentCaseData as $i => $data) {
            $feesTotal   = $urgentFees[$i]['total'];
            $feesPaid    = $urgentFees[$i]['paid'];
            $caseNumber  = 'DEMO-' . now()->format('Y') . '-' . str_pad((string)($i + 1), 4, '0', STR_PAD_LEFT);

            // Skip if case_number already exists for this firm
            $exists = DB::table('legal_cases')
                ->where('law_firm_id', $firmId)
                ->where('case_number', $caseNumber)
                ->exists();

            if ($exists) {
                $caseNumber .= '-U';
            }

            $case = CaseModel::withoutGlobalScopes()->create([
                'law_firm_id'       => $firmId,
                'created_by_user_id' => $firmOwner?->id,
                'assigned_lawyer_id' => $firmLawyers->isNotEmpty() ? $firmLawyers[$i % $firmLawyers->count()]->id : null,
                'case_number'       => $caseNumber,
                'title'             => $data['title'],
                'court'             => $data['court'],
                'case_type'         => $data['case_type'],
                'degree'            => $data['degree'],
                'status'            => 'active',
                'priority'          => 'high',
                'fees_total'        => $feesTotal,
                'fees_paid'         => $feesPaid,
                'fees_remaining'    => $feesTotal - $feesPaid,
                'description'       => 'قضية عاجلة تستدعي المتابعة الفورية. الجلسة مقررة اليوم.',
                'start_date'        => now()->subMonths($urgentFees[$i]['monthsAgo']),
                'next_session_date' => $today,
            ]);

            $case->clients()->attach(
                $clients->random(min(2, $clients->count()))->pluck('id'),
                ['role' => 'plaintiff', 'created_at' => now(), 'updated_at' => now()]
            );

            // Sync lawyer pivot
            if ($firmLawyers->isNotEmpty()) {
                $case->lawyers()->sync([$firmLawyers[$i % $firmLawyers->count()]->id]);
            }

            $urgentCases->push($case);
        }

        // ── 2. Court sessions TODAY ──────────────────────────────────────────
        $todaySessionTimes = ['09:00', '10:30', '11:00'];
        foreach ($urgentCases as $idx => $case) {
            CourtSession::withoutGlobalScopes()->create([
                'law_firm_id'   => $firmId,
                'case_id'       => $case->id,
                'court'         => $case->court,
                'session_date'  => $today,
                'session_time'  => $todaySessionTimes[$idx] ?? '10:00',
                'status'        => 'scheduled',
                'notes'         => 'جلسة اليوم – يُرجى الحضور قبل الموعد بـ 30 دقيقة.',
            ]);
        }

        // ── 3. Court sessions TOMORROW ───────────────────────────────────────
        $tomorrowCaseData = [
            ['title' => 'قضية فسخ عقد شراكة تجارية', 'court' => 'محكمة قسنطينة',  'case_type' => 'تجارية',  'degree' => 'ابتدائي'],
            ['title' => 'طعن إداري ضد قرار تعسفي',    'court' => 'مجلس الدولة',     'case_type' => 'إدارية',  'degree' => 'استئناف'],
        ];

        $tomorrowFees = [
            ['total' => 145000, 'paid' => 50000, 'monthsAgo' => 5],
            ['total' => 190000, 'paid' => 70000, 'monthsAgo' => 3],
        ];

        foreach ($tomorrowCaseData as $i => $data) {
            $caseNumber = 'DEMO-TMR-' . now()->format('Y') . '-' . str_pad((string)($i + 1), 3, '0', STR_PAD_LEFT);
            $exists = DB::table('legal_cases')
                ->where('law_firm_id', $firmId)
                ->where('case_number', $caseNumber)
                ->exists();
            if ($exists) {
                $caseNumber .= '-T';
            }

            $feesTotal = $tomorrowFees[$i]['total'];
            $feesPaid  = $tomorrowFees[$i]['paid'];

            $tCase = CaseModel::withoutGlobalScopes()->create([
                'law_firm_id'       => $firmId,
                'created_by_user_id' => $firmOwner?->id,
                'assigned_lawyer_id' => $firmLawyers->isNotEmpty() ? $firmLawyers[$i % $firmLawyers->count()]->id : null,
                'case_number'       => $caseNumber,
                'title'             => $data['title'],
                'court'             => $data['court'],
                'case_type'         => $data['case_type'],
                'degree'            => $data['degree'],
                'status'            => 'active',
                'priority'          => 'medium',
                'fees_total'        => $feesTotal,
                'fees_paid'         => $feesPaid,
                'fees_remaining'    => $feesTotal - $feesPaid,
                'description'       => 'الجلسة القادمة مقررة غداً.',
                'start_date'        => now()->subMonths($tomorrowFees[$i]['monthsAgo']),
                'next_session_date' => $tomorrow,
            ]);

            $tCase->clients()->attach(
                $clients->random(1)->pluck('id'),
                ['role' => 'defendant', 'created_at' => now(), 'updated_at' => now()]
            );

            // Sync lawyer pivot
            if ($firmLawyers->isNotEmpty()) {
                $tCase->lawyers()->sync([$firmLawyers[$i % $firmLawyers->count()]->id]);
            }

            CourtSession::withoutGlobalScopes()->create([
                'law_firm_id'   => $firmId,
                'case_id'       => $tCase->id,
                'court'         => $data['court'],
                'session_date'  => $tomorrow,
                'session_time'  => $i === 0 ? '09:30' : '11:00',
                'status'        => 'scheduled',
                'notes'         => 'جلسة الغد – تأكّد من إعداد المستندات المطلوبة.',
            ]);
        }

        // ── 4. Overdue tasks ─────────────────────────────────────────────────
        $overdueTaskTitles = [
            'إعداد مذكرة الدفاع للقضية رقم 2024/112',
            'تسليم وثيقة التوكيل الرسمي للموكل',
            'مراجعة عقد الشراكة قبل التوقيع',
            'إرسال طلب الإنابة القضائية',
            'متابعة تنفيذ الحكم الصادر بتاريخ 01/02/2026',
            'استلام نسخة الحكم من كتابة الضبط',
        ];

        $allDemoCases = CaseModel::withoutGlobalScopes()
            ->where('law_firm_id', $firmId)
            ->where('status', 'active')
            ->take(6)
            ->get();

        $daysOverdueMap = [5, 8, 11, 14, 17, 20];
        $priorityMap = ['high', 'medium', 'high', 'medium', 'high', 'medium'];
        $statusMap = ['pending', 'in_progress', 'pending', 'in_progress', 'pending', 'in_progress'];

        foreach ($overdueTaskTitles as $idx => $title) {
            $daysOverdue   = $daysOverdueMap[$idx];
            $case          = $allDemoCases->get($idx % $allDemoCases->count());
            $assignedLawyer = $firmLawyers->isNotEmpty()
                ? $firmLawyers[$idx % $firmLawyers->count()]
                : $firmUsers->random();

            $task = Task::withoutGlobalScopes()->create([
                'law_firm_id' => $firmId,
                'case_id'     => $case?->id,
                'assigned_to' => $assignedLawyer->id,
                'title'       => $title,
                'description' => 'مهمة متأخرة – كانت مقررة قبل ' . $daysOverdue . ' أيام.',
                'due_date'    => now()->subDays($daysOverdue)->toDateString(),
                'due_time'    => '17:00',
                'priority'    => $priorityMap[$idx],
                'status'      => $statusMap[$idx],
            ]);

            // Sync lawyer pivot
            $task->lawyers()->sync([$assignedLawyer->id]);
        }

        // ── 5. Unread session-reminder notifications ─────────────────────────
        foreach ($urgentCases as $case) {
            foreach ($firmUsers->take(2) as $user) {
                Notification::withoutGlobalScopes()->create([
                    'law_firm_id' => $firmId,
                    'user_id'     => $user->id,
                    'type'        => 'session_reminder',
                    'title'       => 'تذكير: جلسة اليوم',
                    'body'        => 'لديك جلسة اليوم في ' . $case->court . ' – ' . $case->title,
                    'data'        => json_encode([
                        'case_id'      => $case->id,
                        'case_number'  => $case->case_number,
                        'court'        => $case->court,
                        'session_date' => $today->toDateString(),
                    ]),
                    'read_at'     => null, // unread
                ]);
            }
        }

        // Unread subscription-expiry notification for demo owner
        Notification::withoutGlobalScopes()->create([
            'law_firm_id' => $firmId,
            'user_id'     => $demoUser->id,
            'type'        => 'subscription_expiry',
            'title'       => 'تنبيه: اشتراكك ينتهي قريباً',
            'body'        => 'اشتراكك الحالي سينتهي خلال 7 أيام. يُرجى التجديد للاستمرار في استخدام المنصة.',
            'data'        => json_encode(['expires_in_days' => 7]),
            'read_at'     => null,
        ]);

        // ── 6. Recent audit log activity ─────────────────────────────────────
        $recentActions = [
            ['action' => 'created',  'model' => 'App\\Models\\CaseModel'],
            ['action' => 'updated',  'model' => 'App\\Models\\CourtSession'],
            ['action' => 'viewed',   'model' => 'App\\Models\\Client'],
            ['action' => 'created',  'model' => 'App\\Models\\Task'],
            ['action' => 'updated',  'model' => 'App\\Models\\CaseModel'],
        ];

        $auditModelIds = [7, 12, 4, 19, 11];
        $auditIps = [
            '196.235.44.12',
            '196.235.44.20',
            '196.235.45.31',
            '196.235.45.52',
            '196.235.45.77',
        ];
        $auditMinutesAgo = [10, 24, 37, 51, 66];

        foreach ($recentActions as $i => $entry) {
            AuditLog::withoutGlobalScopes()->create([
                'law_firm_id'  => $firmId,
                'user_id'      => $firmUsers->random()->id,
                'action'       => $entry['action'],
                'model_type'   => $entry['model'],
                'model_id'     => $auditModelIds[$i],
                'old_values'   => $entry['action'] === 'updated' ? json_encode(['status' => 'pending'])   : null,
                'new_values'   => $entry['action'] === 'updated' ? json_encode(['status' => 'in_progress']) : null,
                'ip_address'   => $auditIps[$i],
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at'   => now()->subMinutes($auditMinutesAgo[$i]),
                'updated_at'   => now(),
            ]);
        }

        $this->command->info('✅  DemoScenarioSeeder: سيناريوهات اليوم تم إنشاؤها بنجاح!');
        $this->command->line('   – ' . $urgentCases->count() . ' قضايا عاجلة (جلسات اليوم)');
        $this->command->line('   – 2 جلسات مجدولة غداً');
        $this->command->line('   – ' . count($overdueTaskTitles) . ' مهام متأخرة');
        $this->command->line('   – ' . ($urgentCases->count() * 2 + 1) . ' إشعارات غير مقروءة');
        $this->command->line('   – 5 سجلات تدقيق حديثة');
    }
}
