<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('تأكيد بريدك الإلكتروني على قسطاس')
                ->view('emails.verify-email', [
                    'user' => $notifiable,
                    'verificationUrl' => $url,
                ]);
        });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $lawFirm = $user->lawFirm;
                $latestSubscription = $lawFirm?->subscriptions()->latest()->first();
                $billingContact = $lawFirm
                    ? User::where('law_firm_id', $lawFirm->id)->where('role', 'owner')->orderBy('id')->first()
                    : null;

                $lockedStatuses = Subscription::LOCKED_STATUSES;
                $subscriptionAccessLocked = false;

                if (!$user->isAdmin()) {
                    if (!$user->law_firm_id) {
                        $subscriptionAccessLocked = true;
                    } elseif (!$latestSubscription) {
                        $subscriptionAccessLocked = true;
                    } else {
                        $subscriptionAccessLocked = $latestSubscription->isLockedByStatus()
                            || $latestSubscription->isExpired()
                            || in_array(strtolower((string) ($lawFirm?->subscription_status)), $lockedStatuses, true);
                    }
                }

                $view->with('unreadNotifCount', Notification::where('user_id', Auth::id())->unread()->count());
                $view->with('subscriptionAccessLocked', $subscriptionAccessLocked);
                $view->with('currentFirmSubscription', $latestSubscription);
                $view->with('billingContact', $billingContact);
            }
        });
    }
}
