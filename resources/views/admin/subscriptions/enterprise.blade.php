@extends('layouts.support')

@section('title', 'الحسابات المؤسسية')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">إدارة الحسابات المؤسسية</h1>
            <p class="text-sm text-gray-500 mt-1">تحديث بيانات العقود وحدود المستخدمين للاشتراكات من نوع enterprise.</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">
            <i class="fas fa-arrow-right"></i>
            <span>الرجوع للوحة الاشتراكات</span>
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm font-semibold">
            {{ session('error') }}
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

    @forelse($enterpriseSubscriptions as $subscription)
        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900">{{ $subscription->lawFirm->name ?? '—' }}</h2>
                    <p class="text-xs text-gray-500">{{ $subscription->lawFirm->email ?? '—' }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-bold {{ $subscription->status === 'active' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-700 border-amber-200' }}">
                    {{ $subscription->status }}
                </span>
            </div>

            <form method="POST" action="{{ route('admin.subscriptions.enterprise.update', $subscription->id) }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">اسم الحساب المؤسسي</label>
                    <input type="text" name="enterprise_account_name" value="{{ old('enterprise_account_name', $subscription->enterprise_account_name) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="مثال: Direction Juridique - Groupe X">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">رقم العقد</label>
                    <input type="text" name="contract_number" value="{{ old('contract_number', $subscription->contract_number) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="CTR-2026-001">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">بداية العقد</label>
                    <input type="datetime-local" name="contract_starts_at" value="{{ old('contract_starts_at', optional($subscription->contract_starts_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">نهاية العقد</label>
                    <input type="datetime-local" name="contract_ends_at" value="{{ old('contract_ends_at', optional($subscription->contract_ends_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">حد المستخدمين</label>
                    <input type="number" min="1" name="user_limit" value="{{ old('user_limit', $subscription->user_limit) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="مثال: 50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">دورة الفوترة</label>
                    <select name="billing_cycle" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm">
                        @foreach(['monthly' => 'شهري', 'quarterly' => 'ربع سنوي', 'yearly' => 'سنوي', 'custom' => 'مخصص'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('billing_cycle', $subscription->billing_cycle) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">المبلغ</label>
                    <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $subscription->amount) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">العملة</label>
                    <input type="text" name="currency" value="{{ old('currency', $subscription->currency) }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">الحالة</label>
                    <select name="status" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                        @foreach(['trial','active','expired','suspended','cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $subscription->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 xl:col-span-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-bold hover:bg-[#174a95] transition">
                        <i class="fas fa-floppy-disk"></i>
                        <span>حفظ بيانات الحساب المؤسسي</span>
                    </button>
                </div>
            </form>
        </section>
    @empty
        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-500">
            لا توجد اشتراكات مؤسسية حالياً.
        </div>
    @endforelse

    <div>
        {{ $enterpriseSubscriptions->links() }}
    </div>
</div>
@endsection
