<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قسطاس — @yield('title', 'بوابة الدعم')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-[Cairo] bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        <aside class="w-72 bg-white border-l border-slate-200 p-5 hidden lg:block">
            <div class="mb-6">
                <div class="text-xs font-bold text-slate-500">QISTAS SUPPORT</div>
                <h1 class="text-xl font-extrabold mt-1">بوابة الدعم</h1>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('support.dashboard') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl {{ request()->routeIs('support.dashboard') ? 'bg-[#1c5bb8] text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    <i class="fas fa-chart-pie w-4"></i>
                    <span class="font-semibold text-sm">لوحة الدعم</span>
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.subscriptions.index') ? 'bg-[#1c5bb8] text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    <i class="fas fa-credit-card w-4"></i>
                    <span class="font-semibold text-sm">إدارة الاشتراكات</span>
                </a>
                <a href="{{ route('admin.subscriptions.enterprise') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.subscriptions.enterprise') ? 'bg-[#1c5bb8] text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    <i class="fas fa-building w-4"></i>
                    <span class="font-semibold text-sm">الحسابات المؤسسية</span>
                </a>
            </nav>

            <div class="mt-8 pt-5 border-t border-slate-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 text-sm font-bold px-4 py-2.5 transition">
                        <i class="fas fa-sign-out-alt"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
