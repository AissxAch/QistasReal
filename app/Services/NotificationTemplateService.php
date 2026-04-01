<?php

namespace App\Services;

use App\Models\CaseModel;
use App\Models\CourtSession;
use App\Models\Subscription;
use App\Models\Task;
use App\Models\User;

class NotificationTemplateService
{
    public static function caseCreated(User $actor, CaseModel $case): void
    {
        $recipients = User::where('law_firm_id', $actor->law_firm_id)
            ->where('id', '!=', $actor->id)
            ->get();

        foreach ($recipients as $recipient) {
            NotificationService::send(
                $recipient,
                'case_created',
                'تم إنشاء قضية جديدة',
                "تمت إضافة القضية رقم {$case->case_number} بعنوان {$case->title}.",
                [
                    'entity_type' => 'case',
                    'entity_id' => $case->id,
                    'case_number' => $case->case_number,
                    'action_url' => route('cases.show', $case),
                ]
            );
        }
    }

    public static function sessionCreated(User $actor, CourtSession $session): void
    {
        $title = 'تذكير بجلسة قادمة';
        $body = "تمت جدولة جلسة بتاريخ {$session->session_date?->format('Y-m-d')} الساعة {$session->session_time?->format('H:i')} في {$session->court}.";

        $recipients = User::where('law_firm_id', $actor->law_firm_id)
            ->where('id', '!=', $actor->id)
            ->get();

        foreach ($recipients as $recipient) {
            NotificationService::send(
                $recipient,
                'session_reminder',
                $title,
                $body,
                [
                    'entity_type' => 'session',
                    'entity_id' => $session->id,
                    'case_id' => $session->case_id,
                    'action_url' => route('sessions.show', $session),
                ]
            );
        }
    }

    public static function sessionUpdated(User $actor, CourtSession $session): void
    {
        $recipients = User::where('law_firm_id', $actor->law_firm_id)
            ->where('id', '!=', $actor->id)
            ->get();

        foreach ($recipients as $recipient) {
            NotificationService::send(
                $recipient,
                'session_updated',
                'تحديث على الجلسة',
                "تم تعديل جلسة القضية {$session->case?->case_number}.",
                [
                    'entity_type' => 'session',
                    'entity_id' => $session->id,
                    'case_id' => $session->case_id,
                    'action_url' => route('sessions.show', $session),
                ]
            );
        }
    }

    public static function taskCreated(User $actor, Task $task): void
    {
        $owners = User::where('law_firm_id', $actor->law_firm_id)
            ->where('role', 'owner')
            ->where('id', '!=', $actor->id)
            ->get();

        foreach ($owners as $owner) {
            NotificationService::send(
                $owner,
                'task_created',
                'تم إنشاء مهمة جديدة',
                "تم إنشاء المهمة \"{$task->title}\".",
                [
                    'entity_type' => 'task',
                    'entity_id' => $task->id,
                    'action_url' => route('tasks.show', $task),
                ]
            );
        }
    }

    public static function taskAssigned(User $actor, Task $task): void
    {
        if (!$task->assignedTo || $task->assignedTo->id === $actor->id) {
            return;
        }

        NotificationService::send(
            $task->assignedTo,
            'task_assigned',
            'تم إسناد مهمة جديدة',
            "تم إسناد المهمة \"{$task->title}\" لك.",
            [
                'entity_type' => 'task',
                'entity_id' => $task->id,
                'case_id' => $task->case_id,
                'action_url' => route('tasks.show', $task),
            ]
        );
    }

    public static function enterpriseContractReminder(User $recipient, Subscription $subscription, int $daysRemaining): void
    {
        $firmName = $subscription->lawFirm?->name ?? 'مكتبكم';
        $contractNumber = $subscription->contract_number ?: 'بدون رقم عقد';

        NotificationService::send(
            $recipient,
            'enterprise_contract_reminder',
            'تنبيه قرب انتهاء العقد المؤسسي',
            "عقد الاشتراك المؤسسي للمكتب {$firmName} ({$contractNumber}) سينتهي بعد {$daysRemaining} يوم/أيام.",
            [
                'entity_type' => 'subscription',
                'entity_id' => $subscription->id,
                'subscription_id' => $subscription->id,
                'plan' => $subscription->plan,
                'reminder_days' => $daysRemaining,
                'contract_number' => $subscription->contract_number,
                'action_url' => route('subscription'),
            ]
        );
    }

    public static function enterpriseContractExpired(User $recipient, Subscription $subscription, int $graceDays): void
    {
        $firmName = $subscription->lawFirm?->name ?? 'مكتبكم';
        $contractNumber = $subscription->contract_number ?: 'بدون رقم عقد';

        NotificationService::send(
            $recipient,
            'enterprise_contract_expired',
            'انتهى العقد المؤسسي ودخل فترة السماح',
            "انتهى العقد المؤسسي للمكتب {$firmName} ({$contractNumber}) وتم تفعيل فترة سماح لمدة {$graceDays} أيام قبل التعليق التلقائي.",
            [
                'entity_type' => 'subscription',
                'entity_id' => $subscription->id,
                'subscription_id' => $subscription->id,
                'plan' => $subscription->plan,
                'grace_days' => $graceDays,
                'contract_number' => $subscription->contract_number,
                'action_url' => route('subscription'),
            ]
        );
    }

    public static function enterpriseContractSuspended(User $recipient, Subscription $subscription): void
    {
        $firmName = $subscription->lawFirm?->name ?? 'مكتبكم';
        $contractNumber = $subscription->contract_number ?: 'بدون رقم عقد';

        NotificationService::send(
            $recipient,
            'enterprise_contract_suspended',
            'تم تعليق الحساب المؤسسي',
            "تم تعليق الحساب المؤسسي للمكتب {$firmName} ({$contractNumber}) بعد انتهاء فترة السماح. يرجى التجديد لإعادة التفعيل.",
            [
                'entity_type' => 'subscription',
                'entity_id' => $subscription->id,
                'subscription_id' => $subscription->id,
                'plan' => $subscription->plan,
                'contract_number' => $subscription->contract_number,
                'action_url' => route('subscription'),
            ]
        );
    }
}
