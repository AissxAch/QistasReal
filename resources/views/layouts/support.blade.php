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
    @php
        $supportNavItems = [
            [
                'label' => 'لوحة الدعم',
                'route' => route('support.dashboard'),
                'active' => request()->routeIs('support.dashboard'),
                'icon' => 'fa-chart-pie',
                'description' => 'المتابعة العامة',
            ],
            [
                'label' => 'إدارة المكاتب',
                'route' => route('admin.law-firms.index'),
                'active' => request()->routeIs('admin.law-firms.*'),
                'icon' => 'fa-city',
                'description' => 'المكاتب والمالكون',
            ],
            [
                'label' => 'إدارة الاشتراكات',
                'route' => route('admin.subscriptions.index'),
                'active' => request()->routeIs('admin.subscriptions.index'),
                'icon' => 'fa-credit-card',
                'description' => 'الاشتراكات والمدفوعات',
            ],
            [
                'label' => 'الحسابات المؤسسية',
                'route' => route('admin.subscriptions.enterprise'),
                'active' => request()->routeIs('admin.subscriptions.enterprise'),
                'icon' => 'fa-building',
                'description' => 'العقود المؤسسية',
            ],
        ];
    @endphp

    <div class="min-h-screen flex">
        <aside class="w-80 bg-white border-l border-slate-200 p-5 hidden lg:flex lg:flex-col shadow-sm">
            <div class="mb-6 rounded-2xl bg-gradient-to-br from-[#1c5bb8] to-[#0f2d62] p-4 text-white">
                <div class="text-xs font-bold text-blue-100 tracking-wide">QISTAS SUPPORT</div>
                <h1 class="text-xl font-extrabold mt-1">بوابة الإدارة والدعم</h1>
                <p class="text-xs text-blue-100 mt-2">إدارة المكاتب، الاشتراكات، وطلبات التفعيل من مكان واحد.</p>
            </div>

            <div class="mb-3 px-1">
                <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-[0.18em]">Navigation</div>
            </div>

            <nav class="space-y-2 flex-1">
                @foreach($supportNavItems as $item)
                    <a href="{{ $item['route'] }}" class="flex items-start gap-3 px-3 py-3 rounded-2xl border transition {{ $item['active'] ? 'bg-[#1c5bb8] text-white border-[#1c5bb8] shadow-sm' : 'border-transparent text-slate-700 hover:bg-slate-50 hover:border-slate-200' }}">
                        <span class="mt-0.5 inline-flex items-center justify-center w-9 h-9 rounded-xl {{ $item['active'] ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <i class="fas {{ $item['icon'] }} text-sm"></i>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block font-bold text-sm">{{ $item['label'] }}</span>
                            <span class="block text-xs {{ $item['active'] ? 'text-blue-100' : 'text-slate-500' }} mt-0.5">{{ $item['description'] }}</span>
                        </span>
                    </a>
                @endforeach
            </nav>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xs text-slate-500">الحساب الحالي</div>
                <div class="mt-1 font-extrabold text-slate-900">{{ auth()->user()?->name }}</div>
                <div class="text-xs text-slate-500 mt-1" dir="ltr">{{ auth()->user()?->email }}</div>
            </div>

            <div class="mt-4 pt-5 border-t border-slate-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 text-sm font-bold px-4 py-2.5 transition">
                        <i class="fas fa-sign-out-alt"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-4 lg:p-8">
            <div class="lg:hidden mb-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-xs font-bold text-slate-500">QISTAS SUPPORT</div>
                        <div class="text-lg font-extrabold text-slate-900">بوابة الإدارة والدعم</div>
                    </div>
                    <a href="{{ route('support.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1c5bb8] px-3 py-2 text-white text-sm font-semibold">
                        <i class="fas fa-house"></i>
                        الرئيسية
                    </a>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    @foreach($supportNavItems as $item)
                        <a href="{{ $item['route'] }}" class="rounded-xl border px-3 py-2 text-sm font-semibold {{ $item['active'] ? 'border-[#1c5bb8] bg-[#1c5bb8]/5 text-[#1c5bb8]' : 'border-slate-200 text-slate-700 bg-white' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

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
