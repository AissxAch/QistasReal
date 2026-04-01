@extends('layouts.auth')

@section('title', 'إنشاء حساب')

@section('content')
    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">الاسم الكامل</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">البريد الإلكتروني</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" required>
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="law_firm_name">اسم المكتب</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="law_firm_name" type="text" name="law_firm_name" value="{{ old('law_firm_name') }}" required>
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4 8 4v14M9 9h.01M15 9h.01M9 13h.01M15 13h.01"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="law_firm_phone">هاتف المكتب (اختياري)</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="law_firm_phone" type="text" name="law_firm_phone" value="{{ old('law_firm_phone') }}" dir="ltr">
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.94 1.515l.66 2.64a2 2 0 01-.45 1.82l-1.27 1.27a16 16 0 006.364 6.364l1.27-1.27a2 2 0 011.82-.45l2.64.66A2 2 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">كلمة المرور</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="password" type="password" name="password" required autocomplete="new-password">
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v-4m-8 4v-4"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" required>
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="form-group">
                <label class="checkbox-custom">
                    <input type="checkbox" name="terms" required>
                    <span class="checkbox-mark"></span>
                    <span class="checkbox-text">
                        أوافق على <a href="{{ route('terms.show') }}" target="_blank" class="link">شروط الاستخدام</a> و <a href="{{ route('policy.show') }}" target="_blank" class="link">سياسة الخصوصية</a>
                    </span>
                </label>
            </div>
        @endif

        <button type="submit" class="btn-primary">إنشاء حساب</button>
    </form>

    <div class="divider">
        <span>لديك حساب بالفعل؟</span>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="link font-bold">تسجيل الدخول</a>
    </div>
@endsection