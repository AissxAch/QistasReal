@extends('layouts.app')

@section('title', 'الاشتراك')

@section('content')
@php
    $currentPlanKey = strtolower((string) optional($subscription)->plan);

    $subscriptionStatus = optional($subscription)->status;
    $statusLabel = match ($subscriptionStatus) {
        'active' => 'نشط',
        'trial' => 'تجريبي',
        'suspended' => 'معلّق',
        default => 'منتهي',
    };

    $statusClass = match ($subscriptionStatus) {
        'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'trial' => 'bg-amber-100 text-amber-700 border-amber-200',
        'suspended' => 'bg-red-100 text-red-700 border-red-200',
        default => 'bg-red-100 text-red-700 border-red-200',
    };

    $paymentStatusClass = [
        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
        'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'failed' => 'bg-red-100 text-red-700 border-red-200',
    ];

    $paymentStatusLabel = [
        'pending' => 'قيد الانتظار',
        'completed' => 'مكتمل',
        'failed' => 'فشل',
    ];
@endphp

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">الاشتراك والمدفوعات</h1>
            <p class="text-sm text-gray-500 mt-1">متابعة حالة اشتراك المكتب، التجديد اليدوي للمسموح لهم، ومعرفة الجهة التي تدير الاشتراك.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(($subscriptionAccessLocked ?? false) && $subscription)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-red-800">تم تقييد الوصول لبقية أقسام المنصة</h2>
                    <p class="text-sm text-red-700 mt-1">بسبب انتهاء أو تعليق الاشتراك، تم إبقاء الوصول فقط إلى الاشتراك والإعدادات حتى تتم التسوية أو التجديد.</p>
                </div>
            </div>
        </div>
    @endif

    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">الخطة الحالية</h2>

        @if($subscription)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <p class="text-xl font-extrabold text-gray-900">{{ $plans[$currentPlanKey]['name'] ?? $subscription->plan }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-bold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">تاريخ الانتهاء: {{ optional($subscription->ends_at)->format('Y-m-d') ?? 'غير محدد' }}</p>
                    <p class="text-sm text-gray-600">الأيام المتبقية: {{ $subscription->daysRemaining() }}</p>
                    @if($subscription->contract_number)
                        <p class="text-sm text-gray-600">رقم العقد: {{ $subscription->contract_number }}</p>
                    @endif
                </div>

                <div class="rounded-2xl border {{ $canManageSubscription ? 'border-emerald-200 bg-emerald-50/60' : 'border-blue-200 bg-blue-50/60' }} p-4">
                    <h3 class="text-sm font-extrabold {{ $canManageSubscription ? 'text-emerald-800' : 'text-blue-800' }} mb-2">من يدير هذا الاشتراك؟</h3>
                    <p class="text-sm {{ $canManageSubscription ? 'text-emerald-700' : 'text-blue-700' }}">{{ $subscriptionManagerLabel }}</p>

                    @if($billingContact)
                        <div class="mt-3 text-sm text-gray-700 space-y-1">
                            <p><span class="font-bold">مسؤول الاشتراك:</span> {{ $billingContact->name }}</p>
                            <p><span class="font-bold">البريد:</span> {{ $billingContact->email }}</p>
                            @if($billingContact->phone)
                                <p><span class="font-bold">الهاتف:</span> {{ $billingContact->phone }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if($subscription->isExpired())
                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    انتهت صلاحية اشتراكك. @if($canManageSubscription) يمكنك إرسال طلب تجديد من الأسفل. @else يرجى التواصل مع مسؤول الاشتراك أو المؤسسة المديرة. @endif
                </div>
            @endif
        @else
            <div class="space-y-4">
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                    لا يوجد اشتراك نشط
                </div>

                @if(Auth::user()?->isOwner())
                    <form method="POST" action="{{ route('subscription.purchase-basic') }}" class="rounded-2xl border border-gray-200 p-5 space-y-4">
                        @csrf
                        <h3 class="text-base font-extrabold text-gray-900">طلب شراء الخطة الأساسية</h3>
                        <p class="text-sm text-gray-600">يمكنك إرسال طلب شراء الخطة الأساسية وسيظهر مباشرة في لوحة السوبر أدمن للمراجعة والاعتماد.</p>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">طريقة الدفع</label>
                            <select name="payment_method" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                                <option value="ccp">CCP</option>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="cash">دفع مباشر</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">رقم المرجع / العملية</label>
                            <input type="text" name="transaction_id" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="اختياري">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">ملاحظات</label>
                            <textarea name="note" rows="3" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="مثال: تم الإيداع في CCP اليوم صباحًا"></textarea>
                        </div>

                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm text-gray-700">
                            مبلغ الخطة الأساسية: <span class="font-extrabold text-gray-900">2,500.00 DZD</span>
                        </div>

                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-bold hover:bg-[#174a95] transition">
                            <i class="fas fa-paper-plane"></i>
                            <span>إرسال طلب شراء الخطة الأساسية</span>
                        </button>
                    </form>
                @else
                    <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                        هذا الطلب متاح فقط لمالك المكتب.
                    </div>
                @endif
            </div>
        @endif
    </section>

    @if($subscription)
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-900">إدارة الاشتراك</h2>
                <span class="text-xs px-3 py-1 rounded-full {{ $canManageSubscription ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }} font-bold">
                    {{ $canManageSubscription ? 'إدارة ذاتية' : 'إدارة مركزية' }}
                </span>
            </div>

            @if($canManageSubscription)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <h3 class="text-base font-extrabold text-emerald-800">يمكنك طلب التجديد بنفسك</h3>
                        <p class="text-sm text-emerald-700 mt-2">بما أنك مالك المكتب وخطتك شهرية، يمكنك إرسال طلب تجديد يدوي عبر CCP أو التحويل البنكي، وسيتم اعتماده من الإدارة بعد المراجعة.</p>
                    </div>

                    <form method="POST" action="{{ route('subscription.renew') }}" class="rounded-2xl border border-gray-200 p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">طريقة الدفع</label>
                            <select name="payment_method" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                                <option value="ccp">CCP</option>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="cash">دفع مباشر</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">رقم المرجع / العملية</label>
                            <input type="text" name="transaction_id" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="اختياري">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">ملاحظات</label>
                            <textarea name="note" rows="3" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="مثال: تم الإيداع في CCP اليوم صباحًا"></textarea>
                        </div>
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm text-gray-700">
                            مبلغ التجديد الحالي: <span class="font-extrabold text-gray-900">{{ number_format((float) $subscription->amount, 2) }} {{ $subscription->currency }}</span>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-bold hover:bg-[#174a95] transition">
                            <i class="fas fa-paper-plane"></i>
                            <span>إرسال طلب التجديد</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                    <h3 class="text-base font-extrabold text-blue-800">الاشتراك مدار من جهة أخرى</h3>
                    <p class="text-sm text-blue-700 mt-2">لا يمكنك تعديل هذا الاشتراك أو إرسال طلبات تجديد من هذا الحساب. @if($isEnterpriseManaged) هذا لأن الخطة مؤسسية وتُدار مركزيًا بعقد خاص. @else هذا لأن إدارة الاشتراك محصورة في مالك المكتب. @endif</p>
                    @if($billingContact)
                        <p class="text-sm text-blue-700 mt-3">يرجى التواصل مع <span class="font-bold">{{ $billingContact->name }}</span> على {{ $billingContact->email }}{{ $billingContact->phone ? ' / ' . $billingContact->phone : '' }}.</p>
                    @endif
                </div>
            @endif
        </section>
    @endif

    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">مقارنة الخطط</h2>
            <a href="https://wa.me/213791036692" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-semibold hover:bg-[#174a95] transition">
                <i class="fas fa-comments"></i>
                <span>تواصل معنا للترقية</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($displayPlans as $key => $plan)
                @php $isCurrent = $subscription && strtolower((string) $subscription->plan) === $key; @endphp

                <div class="relative rounded-2xl border {{ $isCurrent ? 'border-[#1c5bb8]' : 'border-gray-200' }} bg-white p-5 shadow-sm">
                    @if($isCurrent)
                        <span class="absolute top-3 left-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-[#1c5bb8] text-white">
                            خطتك الحالية
                        </span>
                    @endif

                    <h3 class="text-lg font-extrabold text-gray-900">{{ $plan['name'] }}</h3>
                    @if($key === 'enterprise')
                        <p class="mt-2 text-lg font-black text-[#1c5bb8]">حسب العقد</p>
                    @else
                        <p class="mt-2 text-2xl font-black text-[#1c5bb8]">
                            {{ number_format($plan['price'], 0) }}
                            <span class="text-sm font-semibold text-gray-600">د.ج / شهر</span>
                        </p>
                    @endif

                    <ul class="mt-4 space-y-2 text-sm text-gray-700">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-600"></i><span>عدد المستخدمين: {{ $plan['users'] }}</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-600"></i><span>عدد القضايا: {{ $plan['cases'] }}</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-600"></i><span>{{ $key === 'enterprise' ? 'إدارة بعقد مؤسسي وفوترة مخصصة' : 'دعم فني عبر واتساب' }}</span></li>
                    </ul>
                </div>
            @endforeach
        </div>
    </section>

    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-5">سجل المدفوعات</h2>

        @if($payments->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600">
                لا توجد مدفوعات مسجلة
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-500">
                            <th class="text-right font-bold py-3 px-2">التاريخ</th>
                            <th class="text-right font-bold py-3 px-2">المبلغ</th>
                            <th class="text-right font-bold py-3 px-2">طريقة الدفع</th>
                            <th class="text-right font-bold py-3 px-2">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            @php
                                $status = strtolower((string) $payment->status);
                                $badgeClass = $paymentStatusClass[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                $badgeLabel = $paymentStatusLabel[$status] ?? ($payment->status ?: 'غير محدد');
                            @endphp
                            <tr class="border-b border-gray-100 last:border-0">
                                <td class="py-3 px-2 text-gray-700">{{ optional($payment->created_at)->format('Y-m-d') ?? '—' }}</td>
                                <td class="py-3 px-2 font-bold text-gray-900">{{ number_format((float) $payment->amount, 2) }} {{ $payment->currency ?? 'DZD' }}</td>
                                <td class="py-3 px-2 text-gray-700">{{ $payment->payment_method ?? '—' }}</td>
                                <td class="py-3 px-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-xs font-bold {{ $badgeClass }}">
                                        {{ $badgeLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
