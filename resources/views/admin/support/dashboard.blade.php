@extends('layouts.support')

@section('title', 'لوحة الدعم')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900">لوحة دعم المنصة</h1>
        <p class="text-sm text-slate-500 mt-1">اختر مكتبًا أولاً، ثم ادخل إلى أقسامه التشغيلية (القضايا، العملاء، المهام...).</p>
    </div>

    <section class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h2 class="text-lg font-bold">سياق المكتب</h2>
                <p class="text-sm text-slate-500 mt-1">كل عملياتك التشغيلية ستطبق على المكتب المحدد هنا.</p>
            </div>
            @if($currentFirm)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                    المكتب الحالي: {{ $currentFirm->name }}
                </span>
            @endif
        </div>

        <form method="POST" action="{{ route('support.firm-context') }}" class="mt-4 flex flex-wrap items-end gap-3">
            @csrf
            <div class="min-w-[260px] flex-1">
                <label class="block text-xs font-bold text-slate-600 mb-1">اختر المكتب</label>
                <select name="law_firm_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm">
                    <option value="">— إلغاء التحديد —</option>
                    @foreach($lawFirms as $firm)
                        <option value="{{ $firm->id }}" {{ $currentFirm && $currentFirm->id === $firm->id ? 'selected' : '' }}>
                            {{ $firm->name }} ({{ $firm->subscription_status ?? 'غير محدد' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#1c5bb8] hover:bg-[#174a95] text-white text-sm font-semibold px-4 py-2.5 transition">
                <i class="fas fa-check"></i>
                تطبيق
            </button>
        </form>

        @if($currentFirm)
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">لوحة المكتب</a>
                <a href="{{ route('cases.index') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">القضايا</a>
                <a href="{{ route('clients.index') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">العملاء</a>
                <a href="{{ route('tasks.index') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">المهام</a>
            </div>
        @else
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                يجب تحديد مكتب أولاً قبل الدخول إلى الأقسام التشغيلية.
            </div>
        @endif
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">إجمالي المكاتب</p>
            <p class="mt-2 text-2xl font-extrabold">{{ number_format($stats['firms_count']) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">المالكون</p>
            <p class="mt-2 text-2xl font-extrabold">{{ number_format($stats['owners_count']) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">اشتراكات نشطة</p>
            <p class="mt-2 text-2xl font-extrabold">{{ number_format($stats['active_subscriptions']) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">اشتراكات متوقفة</p>
            <p class="mt-2 text-2xl font-extrabold">{{ number_format($stats['expired_subscriptions']) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs text-slate-500">مدفوعات معلقة</p>
            <p class="mt-2 text-2xl font-extrabold">{{ number_format($stats['pending_payments']) }}</p>
        </div>
    </section>

    <section class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold">آخر المدفوعات</h2>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-sm font-semibold text-[#1c5bb8] hover:underline">فتح إدارة الاشتراكات</a>
        </div>

        @if($latestPayments->isEmpty())
            <div class="text-sm text-slate-500">لا توجد مدفوعات حديثة.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-right py-2 px-2">المكتب</th>
                            <th class="text-right py-2 px-2">الخطة</th>
                            <th class="text-right py-2 px-2">المبلغ</th>
                            <th class="text-right py-2 px-2">الحالة</th>
                            <th class="text-right py-2 px-2">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestPayments as $payment)
                            <tr class="border-b border-slate-100 last:border-0">
                                <td class="py-2 px-2">{{ $payment->lawFirm->name ?? '—' }}</td>
                                <td class="py-2 px-2">{{ $payment->subscription->plan ?? '—' }}</td>
                                <td class="py-2 px-2">{{ number_format((float)$payment->amount, 2) }} {{ $payment->currency ?? 'DZD' }}</td>
                                <td class="py-2 px-2">{{ $payment->status }}</td>
                                <td class="py-2 px-2">{{ optional($payment->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
