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