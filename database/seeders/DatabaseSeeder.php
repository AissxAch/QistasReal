<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\CaseModel;
use App\Models\Client;
use App\Models\CourtSession;
use App\Models\LawFirm;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $firmDefinitions = [
            [
                'firm' => [
                    'name' => 'مكتب الميزان للمحاماة والاستشارات',
                    'email' => 'contact@mizan-law.dz',
                    'phone' => '021223344',
                    'address' => 'الجزائر العاصمة - باب الزوار',
                    'tax_number' => 'ALG-TAX-1001',
                    'subscription_status' => 'active',
                    'subscription_ends_at' => now()->addMonths(2),
                ],
                'users' => [
                    ['name' => 'عبد القادر بن يونس', 'email' => 'owner@mizan-law.dz', 'role' => 'owner', 'phone' => '0550001001', 'specialty' => 'قضايا تجارية'],
                    ['name' => 'سميرة بوعلام', 'email' => 'lawyer1@mizan-law.dz', 'role' => 'lawyer', 'phone' => '0550001002', 'specialty' => 'نزاعات مدنية'],
                    ['name' => 'ياسين خنشلة', 'email' => 'lawyer2@mizan-law.dz', 'role' => 'lawyer', 'phone' => '0550001003', 'specialty' => 'ملفات عمل'],
                    ['name' => 'حياة رزقي', 'email' => 'assistant@mizan-law.dz', 'role' => 'assistant', 'phone' => '0550001004', 'specialty' => 'متابعة الجلسات'],
                ],
                'clients' => [
                    ['name' => 'شركة الأفق للتوريد', 'phone' => '023110011', 'email' => 'legal@ofok.dz', 'address' => 'سطيف', 'id_number' => 'RC-16B-22511'],
                    ['name' => 'محمد بلحاج', 'phone' => '0661122334', 'email' => 'm.belhadj@example.dz', 'address' => 'البليدة', 'id_number' => 'ID-ALG-214512'],
                    ['name' => 'مؤسسة النور للبناء', 'phone' => '035224466', 'email' => 'admin@nour-build.dz', 'address' => 'قسنطينة', 'id_number' => 'RC-25C-88721'],
                    ['name' => 'ليلى شعبي', 'phone' => '0776655443', 'email' => 'l.chaabi@example.dz', 'address' => 'تيبازة', 'id_number' => 'ID-ALG-118900'],
                ],
                'cases' => [
                    ['case_number' => 'ALG-2026-001', 'title' => 'نزاع عقد توريد بين شركتين', 'court' => 'محكمة سيدي محمد', 'case_type' => 'تجارية', 'degree' => 'ابتدائي', 'status' => 'active', 'priority' => 'high', 'fees_total' => 180000, 'fees_paid' => 70000, 'description' => 'دعوى فسخ عقد توريد مع مطالبة بالتعويض.'],
                    ['case_number' => 'ALG-2026-002', 'title' => 'مطالبة بأجور متأخرة', 'court' => 'محكمة الجزائر - قسم اجتماعي', 'case_type' => 'اجتماعية', 'degree' => 'ابتدائي', 'status' => 'active', 'priority' => 'medium', 'fees_total' => 95000, 'fees_paid' => 35000, 'description' => 'مطالبة عامل بمستحقات نهاية الخدمة والأجور المتأخرة.'],
                    ['case_number' => 'ALG-2026-003', 'title' => 'استئناف حكم تعويض مدني', 'court' => 'مجلس قضاء الجزائر', 'case_type' => 'مدنية', 'degree' => 'استئناف', 'status' => 'suspended', 'priority' => 'low', 'fees_total' => 120000, 'fees_paid' => 120000, 'description' => 'استئناف جزئي حول تقدير قيمة التعويض.'],
                ],
                'subscription' => [
                    'plan' => 'office',
                    'status' => 'active',
                    'starts_at' => now()->subMonths(4),
                    'ends_at' => now()->addMonths(2),
                    'amount' => 5000,
                    'currency' => 'DZD',
                ],
            ],
            [
                'firm' => [
                    'name' => 'مكتب قسنطينة للتحكيم والنزاعات',
                    'email' => 'info@const-legal.dz',
                    'phone' => '031556677',
                    'address' => 'قسنطينة - المدينة الجديدة',
                    'tax_number' => 'ALG-TAX-2002',
                    'subscription_status' => 'active',
                    'subscription_ends_at' => now()->addMonths(1),
                ],
                'users' => [
                    ['name' => 'نور الدين مزهود', 'email' => 'owner@const-legal.dz', 'role' => 'owner', 'phone' => '0551002001', 'specialty' => 'تحكيم تجاري'],
                    ['name' => 'آمال فرحات', 'email' => 'lawyer1@const-legal.dz', 'role' => 'lawyer', 'phone' => '0551002002', 'specialty' => 'قانون شركات'],
                    ['name' => 'ربيع شارف', 'email' => 'assistant@const-legal.dz', 'role' => 'assistant', 'phone' => '0551002003', 'specialty' => 'تنسيق ملفات'],
                ],
                'clients' => [
                    ['name' => 'شركة الشرق للصناعة', 'phone' => '031889900', 'email' => 'compliance@east-industry.dz', 'address' => 'قسنطينة', 'id_number' => 'RC-25D-19001'],
                    ['name' => 'سناء عطية', 'phone' => '0667788990', 'email' => 's.atia@example.dz', 'address' => 'جيجل', 'id_number' => 'ID-ALG-554321'],
                    ['name' => 'مؤسسة البناء الحديث', 'phone' => '031443322', 'email' => 'admin@modern-build.dz', 'address' => 'سكيكدة', 'id_number' => 'RC-21M-88310'],
                ],
                'cases' => [
                    ['case_number' => 'CST-2026-011', 'title' => 'نزاع تأخر تسليم مشروع سكني', 'court' => 'محكمة قسنطينة', 'case_type' => 'مدنية', 'degree' => 'ابتدائي', 'status' => 'active', 'priority' => 'high', 'fees_total' => 210000, 'fees_paid' => 80000, 'description' => 'دعوى ضد مقاول بسبب التأخر في التسليم وإخلال بشروط العقد.'],
                    ['case_number' => 'CST-2026-012', 'title' => 'تنفيذ حكم تجاري', 'court' => 'محكمة قسنطينة - قسم تنفيذ', 'case_type' => 'تجارية', 'degree' => 'تنفيذ', 'status' => 'active', 'priority' => 'medium', 'fees_total' => 130000, 'fees_paid' => 45000, 'description' => 'متابعة إجراءات التنفيذ الجبري لحكم نهائي.'],
                ],
                'subscription' => [
                    'plan' => 'basic',
                    'status' => 'active',
                    'starts_at' => now()->subMonths(2),
                    'ends_at' => now()->addMonth(),
                    'amount' => 2500,
                    'currency' => 'DZD',
                ],
            ],
            [
                'firm' => [
                    'name' => 'مؤسسة العدالة المتحدة للخدمات القانونية',
                    'email' => 'contracts@justice-united.dz',
                    'phone' => '023778899',
                    'address' => 'وهران - حي الصنوبر',
                    'tax_number' => 'ALG-TAX-3003',
                    'subscription_status' => 'expired',
                    'subscription_ends_at' => now()->subDays(3),
                ],
                'users' => [
                    ['name' => 'جمال صدوق', 'email' => 'owner@justice-united.dz', 'role' => 'owner', 'phone' => '0552003001', 'specialty' => 'عقود دولية'],
                    ['name' => 'أسماء غربي', 'email' => 'lawyer1@justice-united.dz', 'role' => 'lawyer', 'phone' => '0552003002', 'specialty' => 'تحصيل ديون'],
                    ['name' => 'فريد كمال', 'email' => 'lawyer2@justice-united.dz', 'role' => 'lawyer', 'phone' => '0552003003', 'specialty' => 'نزاعات عقارية'],
                    ['name' => 'رغدة مهدي', 'email' => 'assistant@justice-united.dz', 'role' => 'assistant', 'phone' => '0552003004', 'specialty' => 'خدمة عملاء'],
                ],
                'clients' => [
                    ['name' => 'شركة المتوسط اللوجستية', 'phone' => '041223355', 'email' => 'legal@medlog.dz', 'address' => 'وهران', 'id_number' => 'RC-31W-77660'],
                    ['name' => 'عمار بركاني', 'phone' => '0778899001', 'email' => 'a.barkani@example.dz', 'address' => 'عين تموشنت', 'id_number' => 'ID-ALG-889031'],
                    ['name' => 'مجمع الأندلس للاستثمار', 'phone' => '041667788', 'email' => 'board@andalus-invest.dz', 'address' => 'تلمسان', 'id_number' => 'RC-13A-20448'],
                    ['name' => 'هنية قادة', 'phone' => '0660003344', 'email' => 'h.kadda@example.dz', 'address' => 'وهران', 'id_number' => 'ID-ALG-601992'],
                ],
                'cases' => [
                    ['case_number' => 'ORN-2026-021', 'title' => 'نزاع حول عقد شراكة استثمارية', 'court' => 'محكمة وهران', 'case_type' => 'تجارية', 'degree' => 'استئناف', 'status' => 'active', 'priority' => 'high', 'fees_total' => 320000, 'fees_paid' => 120000, 'description' => 'مطالبة بالتعويض عن إخلال شريك بالتزاماته العقدية.'],
                    ['case_number' => 'ORN-2026-022', 'title' => 'إخلاء عقار تجاري', 'court' => 'محكمة وهران - قسم عقاري', 'case_type' => 'عقارية', 'degree' => 'ابتدائي', 'status' => 'closed', 'priority' => 'medium', 'fees_total' => 140000, 'fees_paid' => 140000, 'description' => 'حكم نهائي بالإخلاء وتسوية الرسوم.'],
                    ['case_number' => 'ORN-2026-023', 'title' => 'منازعة إدارية مع جهة محلية', 'court' => 'المحكمة الإدارية بوهران', 'case_type' => 'إدارية', 'degree' => 'ابتدائي', 'status' => 'active', 'priority' => 'medium', 'fees_total' => 160000, 'fees_paid' => 60000, 'description' => 'طعن في قرار إداري متعلق برخصة نشاط.'],
                ],
                'subscription' => [
                    'plan' => 'enterprise',
                    'status' => 'expired',
                    'starts_at' => now()->subYear(),
                    'ends_at' => now()->subDays(3),
                    'amount' => 0,
                    'currency' => 'DZD',
                    'contract_number' => 'ENT-ORN-2025-09',
                    'contract_starts_at' => now()->subYear(),
                    'contract_ends_at' => now()->subDays(3),
                    'user_limit' => 25,
                    'billing_cycle' => 'yearly',
                    'enterprise_account_name' => 'Justice United Group',
                ],
            ],
        ];

        foreach ($firmDefinitions as $firmDefinition) {
            $firm = LawFirm::create($firmDefinition['firm']);

            $createdUsers = [];

            foreach ($firmDefinition['users'] as $index => $userData) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'law_firm_id' => $firm->id,
                    'role' => $userData['role'],
                    'phone' => $userData['phone'] ?? null,
                    'specialty' => $userData['specialty'] ?? null,
                    'bio' => 'عضو فريق قانوني في ' . $firm->name,
                    'email_verified_at' => now(),
                    'activated_at' => now(),
                    'invited_at' => $index === 0 ? null : now()->subDays(10),
                    'invitation_expires_at' => $index === 0 ? null : now()->subDays(3),
                ]);

                $createdUsers[] = $user;
            }

            $owner = collect($createdUsers)->firstWhere('role', 'owner');

            User::create([
                'name' => 'مراد بن سالم',
                'email' => 'pending' . $firm->id . '@qistas.test',
                'password' => Hash::make('password'),
                'law_firm_id' => $firm->id,
                'role' => 'assistant',
                'phone' => '0559' . str_pad((string) $firm->id, 6, '0', STR_PAD_LEFT),
                'specialty' => 'أرشفة رقمية',
                'bio' => 'دعوة قيد التفعيل.',
                'invited_by_user_id' => $owner?->id,
                'invited_at' => now()->subHours(2),
                'invitation_expires_at' => now()->addDays(7)->subHours(2),
                'activated_at' => null,
            ]);

            $lawyers = collect($createdUsers)->where('role', 'lawyer')->values();

            $clientIndex = 0;
            $clients = collect($firmDefinition['clients'])->map(function (array $clientData) use ($firm, $lawyers, &$clientIndex) {
                $client = Client::create([
                    'law_firm_id' => $firm->id,
                    'name' => $clientData['name'],
                    'phone' => $clientData['phone'],
                    'email' => $clientData['email'],
                    'address' => $clientData['address'],
                    'id_number' => $clientData['id_number'],
                    'notes' => 'عميل فعّال ضمن المكتب.',
                ]);

                // Sync lawyer pivot
                if ($lawyers->isNotEmpty()) {
                    $client->lawyers()->sync([$lawyers[$clientIndex % $lawyers->count()]->id]);
                }
                $clientIndex++;

                return $client;
            });

            $cases = collect($firmDefinition['cases'])->values()->map(function (array $caseData, int $i) use ($firm, $owner, $lawyers) {
                $feesRemaining = max((float) $caseData['fees_total'] - (float) $caseData['fees_paid'], 0);

                $case = CaseModel::create([
                    'law_firm_id' => $firm->id,
                    'created_by_user_id' => $owner?->id,
                    'assigned_lawyer_id' => $lawyers->isNotEmpty() ? $lawyers[$i % $lawyers->count()]->id : null,
                    'case_number' => $caseData['case_number'],
                    'title' => $caseData['title'],
                    'court' => $caseData['court'],
                    'case_type' => $caseData['case_type'],
                    'degree' => $caseData['degree'],
                    'status' => $caseData['status'],
                    'priority' => $caseData['priority'],
                    'start_date' => now()->subMonths(6 - $i),
                    'next_session_date' => now()->addDays(10 + ($i * 5)),
                    'fees_total' => $caseData['fees_total'],
                    'fees_paid' => $caseData['fees_paid'],
                    'fees_remaining' => $feesRemaining,
                    'description' => $caseData['description'],
                ]);

                // Sync lawyer pivot
                if ($lawyers->isNotEmpty()) {
                    $case->lawyers()->sync([$lawyers[$i % $lawyers->count()]->id]);
                }

                return $case;
            });

            foreach ($cases as $index => $case) {
                $primaryClient = $clients[$index % $clients->count()];
                $secondaryClient = $clients[($index + 1) % $clients->count()];

                $case->clients()->attach($primaryClient->id, [
                    'role' => 'plaintiff',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $case->clients()->attach($secondaryClient->id, [
                    'role' => 'defendant',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                CourtSession::create([
                    'law_firm_id' => $firm->id,
                    'case_id' => $case->id,
                    'session_date' => now()->addDays(7 + ($index * 4)),
                    'session_time' => sprintf('%02d:00:00', 9 + $index),
                    'court' => $case->court,
                    'room' => 'قاعة ' . ($index + 2),
                    'notes' => 'جلسة متابعة مرفقة بمحضر تحضيري.',
                    'status' => 'scheduled',
                ]);

                $assignee = collect($createdUsers)->whereIn('role', ['owner', 'lawyer'])->values()[$index % max(1, collect($createdUsers)->whereIn('role', ['owner', 'lawyer'])->count())];

                $task = Task::create([
                    'law_firm_id' => $firm->id,
                    'case_id' => $case->id,
                    'assigned_to' => $assignee->id,
                    'title' => 'مراجعة ملف القضية: ' . $case->case_number,
                    'description' => 'تحضير مذكرة الرد وتدقيق المستندات الداعمة قبل الجلسة.',
                    'due_date' => now()->addDays(3 + ($index * 2)),
                    'due_time' => '14:00:00',
                    'priority' => $index === 0 ? 'high' : 'medium',
                    'status' => $index === 1 ? 'in_progress' : 'pending',
                ]);

                // Sync task lawyer pivot
                if ($assignee->role === 'lawyer') {
                    $task->lawyers()->sync([$assignee->id]);
                } elseif ($lawyers->isNotEmpty()) {
                    $task->lawyers()->sync([$lawyers[$index % $lawyers->count()]->id]);
                }
            }

            $subscriptionData = $firmDefinition['subscription'];

            $subscription = Subscription::withoutGlobalScopes()->create([
                'law_firm_id' => $firm->id,
                'plan' => $subscriptionData['plan'],
                'status' => $subscriptionData['status'],
                'starts_at' => $subscriptionData['starts_at'],
                'ends_at' => $subscriptionData['ends_at'],
                'trial_ends_at' => null,
                'amount' => $subscriptionData['amount'],
                'currency' => $subscriptionData['currency'],
                'contract_number' => $subscriptionData['contract_number'] ?? null,
                'contract_starts_at' => $subscriptionData['contract_starts_at'] ?? null,
                'contract_ends_at' => $subscriptionData['contract_ends_at'] ?? null,
                'user_limit' => $subscriptionData['user_limit'] ?? null,
                'billing_cycle' => $subscriptionData['billing_cycle'] ?? null,
                'enterprise_account_name' => $subscriptionData['enterprise_account_name'] ?? null,
            ]);

            Payment::create([
                'law_firm_id' => $firm->id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->amount,
                'currency' => $subscription->currency,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'transaction_id' => 'TRX-FIRM-' . str_pad((string) $firm->id, 3, '0', STR_PAD_LEFT) . '-001',
                'payment_data' => [
                    'note' => 'دفعة موثقة ومؤكدة من الإدارة.',
                    'reviewed_at' => now()->subDays(5)->toDateTimeString(),
                ],
            ]);

            Payment::create([
                'law_firm_id' => $firm->id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->amount,
                'currency' => $subscription->currency,
                'payment_method' => 'ccp',
                'status' => 'pending',
                'transaction_id' => null,
                'payment_data' => [
                    'renewal_request' => true,
                    'note' => 'طلب تجديد قيد المراجعة.',
                    'requested_at' => now()->toDateTimeString(),
                ],
            ]);

            Notification::create([
                'law_firm_id' => $firm->id,
                'user_id' => $owner?->id,
                'type' => 'subscription_status',
                'title' => 'تحديث حالة الاشتراك',
                'body' => 'تم تحديث حالة الاشتراك للمكتب: ' . $firm->subscription_status,
                'data' => ['subscription_id' => $subscription->id],
                'read_at' => null,
            ]);

            foreach ($cases as $case) {
                Notification::create([
                    'law_firm_id' => $firm->id,
                    'user_id' => $owner?->id,
                    'type' => 'session_reminder',
                    'title' => 'جلسة قادمة للقضية ' . $case->case_number,
                    'body' => 'لديك جلسة قادمة مرتبطة بالقضية: ' . $case->title,
                    'data' => ['case_id' => $case->id],
                    'read_at' => null,
                ]);
            }

            AuditLog::withoutGlobalScopes()->create([
                'law_firm_id' => $firm->id,
                'user_id' => $owner?->id,
                'action' => 'seed_initialized',
                'model_type' => LawFirm::class,
                'model_id' => $firm->id,
                'old_values' => null,
                'new_values' => [
                    'message' => 'تمت تهيئة بيانات تجريبية واقعية للمكتب.',
                    'cases_count' => $cases->count(),
                    'clients_count' => $clients->count(),
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
            ]);
        }

        User::updateOrCreate(
            ['email' => 'demo@qistas.test'],
            [
                'name' => 'مالك تجريبي',
                'password' => Hash::make('password'),
                'law_firm_id' => LawFirm::first()?->id,
                'role' => 'owner',
                'phone' => '0559990000',
                'specialty' => 'إدارة مكتب',
                'bio' => 'حساب تجريبي للدخول السريع.',
                'email_verified_at' => now(),
                'activated_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@qistas.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('monsf2004'),
                'law_firm_id' => null,
                'role' => 'admin',
                'phone' => null,
                'specialty' => 'إدارة النظام',
                'bio' => 'حساب إدارة المنصة.',
                'email_verified_at' => now(),
                'activated_at' => now(),
            ]
        );

        $this->call(DemoScenarioSeeder::class);
    }
}
