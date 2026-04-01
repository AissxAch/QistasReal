@extends('layouts.auth')

@section('title', 'تسجيل الدخول')

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

    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">البريد الإلكتروني</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">كلمة المرور</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="password" type="password" name="password" required autocomplete="current-password">
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 4v-4m-8 4v-4"/>
                </svg>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="checkbox-custom">
                <input type="checkbox" name="remember">
                <span class="checkbox-mark"></span>
                <span class="checkbox-text">تذكرني</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link">نسيت كلمة المرور؟</a>
            @endif
        </div>

        <button type="submit" class="btn-primary">تسجيل الدخول</button>
    </form>

    <div class="divider">
        <span>ليس لديك حساب؟</span>
    </div>

    <div class="text-center">
        <a href="{{ route('register') }}" class="link font-bold">إنشاء حساب جديد</a>
    </div>
@endsection