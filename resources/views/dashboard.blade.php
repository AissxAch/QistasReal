@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
@php
    $today = now();
    $user = auth()->user();
    $firmName = optional($user->lawFirm)->name ?? 'مكتب المحاماة';

    $totalCases = (int) ($stats['total_cases'] ?? 0);
    $activeCases = (int) ($stats['active_cases'] ?? 0);
    $totalClients = (int) ($stats['total_clients'] ?? 0);
    $pendingTasks = (int) ($stats['pending_tasks'] ?? 0);
    $upcomingSessionsCount = (int) ($stats['upcoming_sessions'] ?? 0);

    $feesTotal = (float) ($stats['fees_total'] ?? 0);
    $feesRemaining = (float) ($stats['fees_remaining'] ?? 0);

    $caseStatus = $analytics['case_status'] ?? ['active' => 0, 'pending' => 0, 'closed' => 0];
    $activeCasesPct = $totalCases > 0 ? round(($caseStatus['active'] / $totalCases) * 100) : 0;
    $pendingCasesPct = $totalCases > 0 ? round(($caseStatus['pending'] / $totalCases) * 100) : 0;
    $closedCasesPct = $totalCases > 0 ? max(100 - $activeCasesPct - $pendingCasesPct, 0) : 0;

    $tasksAnalytics = $analytics['tasks'] ?? ['total' => 0, 'pending' => 0, 'completed' => 0];
    $taskCompletionPct = $tasksAnalytics['total'] > 0 ? round(($tasksAnalytics['completed'] / $tasksAnalytics['total']) * 100) : 0;

    $feesAnalytics = $analytics['fees'] ?? ['collected' => 0, 'remaining' => 0, 'total' => 0];
    $feesCollectedPct = $feesAnalytics['total'] > 0 ? round(($feesAnalytics['collected'] / $feesAnalytics['total']) * 100) : 0;

    $shortcuts = [
        ['label' => 'القضايا', 'icon' => 'fa-scale-balanced', 'route' => 'cases.index', 'color' => 'text-blue-700 bg-blue-500/10'],
        ['label' => 'العملاء', 'icon' => 'fa-users', 'route' => 'clients.index', 'color' => 'text-indigo-700 bg-indigo-500/10'],
        ['label' => 'الجلسات', 'icon' => 'fa-calendar-days', 'route' => 'sessions.index', 'color' => 'text-sky-700 bg-sky-500/10'],
        ['label' => 'المهام', 'icon' => 'fa-list-check', 'route' => 'tasks.index', 'color' => 'text-amber-700 bg-amber-500/10'],
        ['label' => 'الفريق', 'icon' => 'fa-user-tie', 'route' => 'team.index', 'color' => 'text-violet-700 bg-violet-500/10'],
        ['label' => 'الإشعارات', 'icon' => 'fa-bell', 'route' => null, 'color' => 'text-gray-700 bg-gray-500/10'],
        ['label' => 'الإعدادات', 'icon' => 'fa-gear', 'route' => 'settings', 'color' => 'text-slate-700 bg-slate-500/10'],
    ];

    $activityItems = collect()
        ->merge($upcoming_sessions->take(3)->map(function ($session) {
            return [
                'type' => 'session',
                'title' => 'جلسة: ' . ($session->case->title ?? 'قضية غير محددة'),
                'meta' => optional($session->session_date)->format('Y-m-d') . ' - ' . (optional($session->session_time)->format('H:i') ?? '—'),
                'icon' => 'fa-calendar-check',
                'color' => 'text-sky-700 bg-sky-500/10',
            ];
        }))
        ->merge($pending_tasks->take(3)->map(function ($task) {
            return [
                'type' => 'task',
                'title' => 'مهمة: ' . $task->title,
                'meta' => 'موعد الاستحقاق: ' . ($task->due_date ? $task->due_date->format('Y-m-d') : 'غير محدد'),
                'icon' => 'fa-list-check',
                'color' => 'text-amber-700 bg-amber-500/10',
            ];
        }))
        ->take(6);
@endphp

<div class="space-y-6">
    <section class="relative overflow-hidden rounded-2xl border border-blue-100/70 bg-white shadow-sm p-6 md:p-8">
        <div class="absolute -top-16 -left-10 w-44 h-44 rounded-full bg-blue-100/60 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-14 -right-8 w-36 h-36 rounded-full bg-indigo-100/60 blur-2xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">مرحباً {{ $user->name }}، أهلاً بك في {{ $firmName }}</h1>
                <p class="text-sm text-gray-600">{{ $today->translatedFormat('l، d F Y') }} · {{ $today->format('H:i') }} · متابعة دقيقة لأعمال المكتب.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 w-full lg:w-auto">
                <a href="{{ route('cases.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#1c5bb8] text-white text-sm font-semibold hover:bg-[#174a95] transition shadow-sm">
                    <i class="fas fa-plus-circle text-xs"></i>
                    <span>إضافة قضية</span>
                </a>
                <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-semibold hover:border-[#1c5bb8]/30 hover:text-[#1c5bb8] transition">
                    <i class="fas fa-user-plus text-xs"></i>
                    <span>إضافة عميل</span>
                </a>
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-semibold hover:border-[#1c5bb8]/30 hover:text-[#1c5bb8] transition">
                    <i class="fas fa-list-check text-xs"></i>
                    <span>إضافة مهمة</span>
                </a>
                <a href="{{ route('sessions.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-semibold hover:border-[#1c5bb8]/30 hover:text-[#1c5bb8] transition">
                    <i class="fas fa-calendar-plus text-xs"></i>
                    <span>إضافة جلسة</span>
                </a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6 gap-5">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">إجمالي القضايا</p>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ number_format($totalCases) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-[#1c5bb8] flex items-center justify-center">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">القضايا النشطة</p>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ number_format($activeCases) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-scale-balanced"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">إجمالي العملاء</p>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ number_format($totalClients) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">المهام المعلقة</p>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ number_format($pendingTasks) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-list-check"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">الجلسات القادمة</p>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ number_format($upcomingSessionsCount) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center">
                    <i class="fas fa-calendar-days"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">الأتعاب المتبقية</p>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ number_format($feesRemaining, 2) }}</p>
                    <p class="text-xs text-gray-500">د.ج</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-900">تحليلات الأداء</h2>
                <span class="text-xs text-gray-500">تحديث لحظي حسب بيانات المكتب</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="rounded-2xl border border-gray-100 p-5 bg-gray-50/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-800">توزيع حالات القضايا</h3>
                        <i class="fas fa-chart-pie text-[#1c5bb8]"></i>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>نشطة</span>
                                <span>{{ $activeCasesPct }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $activeCasesPct }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>مجدولة</span>
                                <span>{{ $pendingCasesPct }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
                                <div class="h-full rounded-full bg-amber-500" style="width: {{ $pendingCasesPct }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>مغلقة</span>
                                <span>{{ $closedCasesPct }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
                                <div class="h-full rounded-full bg-slate-500" style="width: {{ $closedCasesPct }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-100 p-5 bg-gray-50/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-800">تقدم المهام</h3>
                        <i class="fas fa-chart-line text-[#1c5bb8]"></i>
                    </div>
                    <div class="mb-4">
                        <div class="h-2.5 rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-full rounded-full bg-[#1c5bb8]" style="width: {{ $taskCompletionPct }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">{{ $taskCompletionPct }}% من المهام مكتمل</p>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="rounded-xl bg-white border border-gray-100 p-2.5">
                            <p class="text-[11px] text-gray-500">الإجمالي</p>
                            <p class="text-sm font-bold text-gray-900">{{ number_format($tasksAnalytics['total']) }}</p>
                        </div>
                        <div class="rounded-xl bg-white border border-gray-100 p-2.5">
                            <p class="text-[11px] text-gray-500">مكتمل</p>
                            <p class="text-sm font-bold text-emerald-700">{{ number_format($tasksAnalytics['completed']) }}</p>
                        </div>
                        <div class="rounded-xl bg-white border border-gray-100 p-2.5">
                            <p class="text-[11px] text-gray-500">معلّق</p>
                            <p class="text-sm font-bold text-amber-700">{{ number_format($tasksAnalytics['pending']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">الأتعاب</h2>
                <i class="fas fa-coins text-[#1c5bb8]"></i>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1.5">
                        <span>المحصّل</span>
                        <span>{{ $feesCollectedPct }}%</span>
                    </div>
                    <div class="h-2.5 rounded-full bg-gray-200 overflow-hidden">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $feesCollectedPct }}%"></div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 p-4 bg-gray-50/40 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">إجمالي الأتعاب</span>
                        <span class="font-bold text-gray-900">{{ number_format($feesAnalytics['total'], 2) }} د.ج</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">المحصّل</span>
                        <span class="font-bold text-emerald-700">{{ number_format($feesAnalytics['collected'], 2) }} د.ج</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">المتبقي</span>
                        <span class="font-bold text-rose-700">{{ number_format($feesAnalytics['remaining'], 2) }} د.ج</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">الجلسات القادمة</h2>
                <a href="{{ route('sessions.index') }}" class="text-sm font-semibold text-[#1c5bb8] hover:underline">عرض الكل</a>
            </div>

            <div class="space-y-3">
                @forelse($upcoming_sessions as $session)
                    @php
                        $sessionStatus = $session->status ?? 'scheduled';
                    @endphp
                    <div class="rounded-xl border border-gray-100 p-4 hover:border-[#1c5bb8]/20 hover:bg-blue-50/30 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $session->case->title ?? 'قضية غير محددة' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ optional($session->session_date)->format('Y-m-d') }} · {{ optional($session->session_time)->format('H:i') ?? '—' }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $session->court ?? '—' }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-xs rounded-full font-medium {{ $sessionStatus === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($sessionStatus === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $sessionStatus === 'completed' ? 'منتهية' : ($sessionStatus === 'cancelled' ? 'ملغاة' : 'مجدولة') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-xmark text-3xl text-gray-300 mb-2"></i>
                        <p>لا توجد جلسات قادمة</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">المهام المعلقة</h2>
                <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-[#1c5bb8] hover:underline">عرض الكل</a>
            </div>

            <div class="space-y-3">
                @forelse($pending_tasks as $task)
                    <div class="rounded-xl border border-gray-100 p-4 hover:border-[#1c5bb8]/20 hover:bg-blue-50/30 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">الاستحقاق: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'غير محدد' }}</p>
                                <p class="text-xs text-gray-600 mt-1">المكلف: {{ optional($task->assignee)->name ?? 'غير محدد' }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-xs rounded-full font-medium {{ $task->priority === 'high' ? 'bg-rose-100 text-rose-700' : ($task->priority === 'medium' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $task->priority === 'high' ? 'عالية' : ($task->priority === 'medium' ? 'متوسطة' : 'منخفضة') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-circle-check text-3xl text-gray-300 mb-2"></i>
                        <p>لا توجد مهام معلقة</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 2xl:grid-cols-3 gap-6">
        <div class="2xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">القضايا الحديثة</h2>
                <a href="{{ route('cases.index') }}" class="text-sm font-semibold text-[#1c5bb8] hover:underline">عرض الكل</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">رقم القضية</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">العنوان</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">عدد العملاء</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">الحالة</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">المتبقي</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($recent_cases as $case)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $case->case_number }}</td>
                                <td class="px-5 py-4 text-sm text-gray-800">{{ $case->title }}</td>
                                <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($case->clients_count ?? 0) }}</td>
                                <td class="px-5 py-4">
                                    @if($case->status == 'active')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">نشطة</span>
                                    @elseif($case->status == 'pending')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">مجدولة</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">مغلقة</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold text-gray-800">{{ number_format($case->fees_remaining, 2) }} د.ج</td>
                                <td class="px-5 py-4 text-left">
                                    <a href="{{ route('cases.show', $case) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-[#1c5bb8] hover:underline">
                                        عرض
                                        <i class="fas fa-arrow-left text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">لا توجد قضايا حديثة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <h2 class="text-base font-bold text-gray-900 mb-4">آخر النشاط</h2>
                <div class="space-y-3">
                    @forelse($activityItems as $item)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg {{ $item['color'] }} flex items-center justify-center shrink-0">
                                <i class="fas {{ $item['icon'] }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $item['title'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $item['meta'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">لا يوجد نشاط حديث.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
