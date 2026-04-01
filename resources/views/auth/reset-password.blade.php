@extends('layouts.auth')

@section('title', 'إعادة تعيين كلمة المرور')

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

    <p style="font-size:0.875rem;color:#4b5563;margin-bottom:1.5rem;line-height:1.7;">
        أدخل بريدك الإلكتروني وكلمة المرور الجديدة لإعادة تعيين حسابك.
    </p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label class="form-label" for="email">البريد الإلكتروني</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">كلمة المرور الجديدة</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="password" type="password" name="password" required autocomplete="new-password">
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
            <div class="form-input-wrapper">
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                <svg class="form-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>

        <button type="submit" class="btn-primary">تعيين كلمة المرور الجديدة</button>
    </form>

    <div style="text-align:center;margin-top:1.5rem;">
        <a href="{{ route('login') }}" class="link" style="font-size:0.875rem;">&#8594; العودة إلى تسجيل الدخول</a>
    </div>

@endsection
