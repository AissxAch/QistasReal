@extends('layouts.support')

@section('title', 'إدارة المكاتب')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">إدارة المكاتب</h1>
            <p class="text-sm text-gray-500 mt-1">إضافة مكاتب جديدة وربط مالك بها قبل إدارة الاشتراكات.</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">
            <i class="fas fa-arrow-right"></i>
            <span>الرجوع لإدارة الاشتراكات</span>
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

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="text-base font-extrabold text-gray-900 mb-4">إضافة مكتب جديد</h2>

            <form method="POST" action="{{ route('admin.law-firms.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-1">اسم المكتب</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">البريد</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" dir="ltr">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" dir="ltr">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-1">العنوان</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-bold hover:bg-[#174a95] transition">
                        <i class="fas fa-building"></i>
                        <span>إنشاء المكتب</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="text-base font-extrabold text-gray-900 mb-4">ربط مالك موجود بمكتب</h2>

            <form method="POST" action="{{ route('admin.law-firms.owners.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-1">المكتب</label>
                    <select name="law_firm_id" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" required>
                        <option value="">اختر مكتبًا</option>
                        @foreach($lawFirmOptions as $firm)
                            <option value="{{ $firm->id }}" @selected((string) old('law_firm_id') === (string) $firm->id)>{{ $firm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-1">البريد الإلكتروني للمستخدم الموجود</label>
                    <input type="email" name="email" value="{{ old('email') }}" list="existing-users-emails" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm" dir="ltr" placeholder="اختر أو اكتب بريد مستخدم موجود" required>
                    <datalist id="existing-users-emails">
                        @foreach($existingUsers as $existingUser)
                            <option value="{{ $existingUser->email }}">{{ $existingUser->name }}{{ $existingUser->law_firm_id ? ' — مرتبط بمكتب' : ' — غير مرتبط بمكتب' }}</option>
                        @endforeach
                    </datalist>
                </div>
                <div class="md:col-span-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    يتم الاختيار فقط من مستخدمين موجودين مسبقًا. إذا كان المستخدم مرتبطًا بمكتب آخر فلن يتم نقله تلقائيًا.
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-black transition">
                        <i class="fas fa-user-shield"></i>
                        <span>ربط المستخدم كمالك للمكتب</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h2 class="text-lg font-bold text-gray-900">قائمة المكاتب</h2>

            <form method="GET" action="{{ route('admin.law-firms.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="بحث باسم المكتب أو البريد" class="rounded-xl border border-gray-200 px-3 py-2 text-sm" dir="ltr">
                <button type="submit" class="px-3 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-semibold">بحث</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80 text-gray-500">
                        <th class="text-right py-3 px-2">المكتب</th>
                        <th class="text-right py-3 px-2">البريد</th>
                        <th class="text-right py-3 px-2">حالة الاشتراك</th>
                        <th class="text-right py-3 px-2">المستخدمون</th>
                        <th class="text-right py-3 px-2">المالكون</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lawFirms as $firm)
                        @php
                            $owners = $firm->users->where('role', 'owner');
                            $hasOwner = $owners->isNotEmpty();
                        @endphp
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="py-3 px-2 font-semibold text-gray-900">{{ $firm->name }}</td>
                            <td class="py-3 px-2 text-gray-600" dir="ltr">{{ $firm->email ?? '—' }}</td>
                            <td class="py-3 px-2">{{ $firm->subscription_status ?? '—' }}</td>
                            <td class="py-3 px-2">{{ number_format($firm->users_count) }}</td>
                            <td class="py-3 px-2">
                                @if($hasOwner)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($owners as $owner)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700">{{ $owner->email }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">بدون مالك</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">لا توجد مكاتب.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $lawFirms->links() }}
        </div>
    </section>
</div>
@endsection
