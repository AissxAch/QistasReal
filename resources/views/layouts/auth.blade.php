<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قسطاس - @yield('title', 'تسجيل الدخول')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                        tajawal: ['Tajawal', 'sans-serif'],
                    },
                    colors: {
                        brand: { 50: '#eef3fb', 100: '#d5e2f5', 200: '#b0c8ed', 300: '#7aa5e0', 400: '#4880d0', 500: '#1c5bb8', 600: '#1a4fa3', 700: '#153e84', 800: '#0f2d62', 900: '#0a1e42' },
                        gold: { 400: '#c9a227', 500: '#b8921a', 600: '#9e7b10' },
                    },
                    boxShadow: { 'card': '0 25px 50px -12px rgba(0, 0, 0, 0.25)' },
                }
            }
        }
    </script>

    {{-- Custom Professional CSS --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <div class="auth-container">
        <div class="auth-bg-blob blob-1"></div>
        <div class="auth-bg-blob blob-2"></div>

        <div class="auth-card">
            <img class="logoimg" src="/assets/fulllogo.png" alt="قسطاس">
            @yield('content')
        </div>
    </div>

</body>
</html>