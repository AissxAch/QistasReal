@extends('layouts.support')

@section('title', 'إدارة الاشتراكات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">لوحة إدارة الاشتراكات</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة مركزية لاشتراكات جميع المكاتب مع مراجعة الدفع اليدوي (CCP / تحويل بنكي).</p>
        </div>
        <a href="{{ route('admin.subscriptions.enterprise') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-semibold hover:bg-[#174a95] transition">
            <i class="fas fa-building"></i>
            <span>إدارة الحسابات المؤسسية</span>
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs text-gray-500">إجمالي المكاتب</p>
            <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ number_format($stats['firms_count']) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs text-gray-500">اشتراكات نشطة</p>
            <p class="mt-2 text-2xl font-extrabold text-emerald-700">{{ number_format($stats['active_subscriptions']) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs text-gray-500">اشتراكات مؤسسية</p>
            <p class="mt-2 text-2xl font-extrabold text-[#1c5bb8]">{{ number_format($stats['enterprise_subscriptions']) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs text-gray-500">مدفوعات معلقة</p>
            <p class="mt-2 text-2xl font-extrabold text-amber-700">{{ number_format($stats['pending_payments']) }}</p>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-4">إضافة اشتراك جديد (يشمل المؤسسي)</h2>

        <form method="POST" action="{{ route('admin.subscriptions.store') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">المكتب</label>
                <select name="law_firm_id" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    <option value="">اختر المكتب</option>
                    @foreach($lawFirms as $firm)
                        <option value="{{ $firm->id }}" @selected(old('law_firm_id') == $firm->id)>
                            {{ $firm->name }} ({{ $firm->users_count }} مستخدم)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">الخطة</label>
                <select name="plan" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    <option value="basic">basic</option>
                    <option value="office">office</option>
                    <option value="premium">premium</option>
                    <option value="enterprise">enterprise / مؤسسي</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">الحالة</label>
                <select name="status" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    <option value="active">active</option>
                    <option value="trial">trial</option>
                    <option value="expired">expired</option>
                    <option value="suspended">suspended</option>
                    <option value="cancelled">cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">المبلغ</label>
                <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', 0) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">العملة</label>
                <input type="text" name="currency" value="{{ old('currency', 'DZD') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">تاريخ البداية</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">تاريخ الانتهاء</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">نهاية الفترة التجريبية</label>
                <input type="datetime-local" name="trial_ends_at" value="{{ old('trial_ends_at') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-2 xl:col-span-4">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-bold hover:bg-[#174a95] transition">
                    <i class="fas fa-plus"></i>
                    <span>إضافة الاشتراك</span>
                </button>
            </div>
        </form>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-4">الاشتراكات الحالية</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-500">
                        <th class="text-right py-3 px-2">المكتب</th>
                        <th class="text-right py-3 px-2">الخطة</th>
                        <th class="text-right py-3 px-2">الحالة</th>
                        <th class="text-right py-3 px-2">الانتهاء</th>
                        <th class="text-right py-3 px-2">المبلغ</th>
                        <th class="text-right py-3 px-2">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        @php
                            $status = strtolower((string) $subscription->status);
                            $statusClass = match($status) {
                                'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'trial' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'expired', 'cancelled', 'suspended' => 'bg-red-100 text-red-700 border-red-200',
                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="py-3 px-2 font-semibold text-gray-900">{{ $subscription->lawFirm->name ?? '—' }}</td>
                            <td class="py-3 px-2">{{ $subscription->plan }}</td>
                            <td class="py-3 px-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-xs font-bold {{ $statusClass }}">{{ $subscription->status }}</span>
                            </td>
                            <td class="py-3 px-2">{{ optional($subscription->ends_at)->format('Y-m-d') ?? '—' }}</td>
                            <td class="py-3 px-2">{{ number_format((float)$subscription->amount, 2) }} {{ $subscription->currency }}</td>
                            <td class="py-3 px-2">
                                <form method="POST" action="{{ route('admin.subscriptions.status', $subscription->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-lg border border-gray-200 px-2 py-1 text-xs">
                                        @foreach(['trial','active','expired','suspended','cancelled'] as $option)
                                            <option value="{{ $option }}" @selected($subscription->status === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-gray-900 text-white text-xs font-semibold">حفظ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">لا توجد اشتراكات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $subscriptions->links() }}
        </div>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-4">المدفوعات المعلقة للمراجعة</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-500">
                        <th class="text-right py-3 px-2">المكتب</th>
                        <th class="text-right py-3 px-2">المبلغ</th>
                        <th class="text-right py-3 px-2">الطريقة</th>
                        <th class="text-right py-3 px-2">الحالة</th>
                        <th class="text-right py-3 px-2">مراجعة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingPayments as $payment)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="py-3 px-2 font-semibold text-gray-900">{{ $payment->lawFirm->name ?? '—' }}</td>
                            <td class="py-3 px-2">{{ number_format((float)$payment->amount, 2) }} {{ $payment->currency }}</td>
                            <td class="py-3 px-2">{{ $payment->payment_method }}</td>
                            <td class="py-3 px-2"><span class="inline-flex px-2.5 py-1 rounded-full border border-amber-200 bg-amber-100 text-amber-700 text-xs font-bold">pending</span></td>
                            <td class="py-3 px-2">
                                <form method="POST" action="{{ route('admin.subscriptions.payments.status', $payment->id) }}" class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-lg border border-gray-200 px-2 py-1 text-xs">
                                        <option value="completed">completed</option>
                                        <option value="failed">failed</option>
                                        <option value="pending">pending</option>
                                    </select>
                                    <input type="text" name="transaction_id" placeholder="رقم العملية" class="rounded-lg border border-gray-200 px-2 py-1 text-xs">
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-[#1c5bb8] text-white text-xs font-semibold">اعتماد</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">لا توجد مدفوعات معلقة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pendingPayments->links() }}
        </div>
    </section>
</div>
@endsection
