@extends('layouts.auth')

@section('title', 'نسيت كلمة المرور')

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

    <p style="font-size:0.875rem;color:#4b5563;margin-bottom:1.5rem;line-height:1.7;">
        نسيت كلمة المرور؟ لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيينها.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
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

        <button type="submit" class="btn-primary">إرسال رابط إعادة التعيين</button>
    </form>

    <div style="text-align:center;margin-top:1.5rem;">
        <a href="{{ route('login') }}" class="link" style="font-size:0.875rem;">&#8594; العودة إلى تسجيل الدخول</a>
    </div>

@endsection
