@extends('layouts.app')

@section('title', 'التقويم')

@section('content')
@php
    $monthName = $startOfMonth->translatedFormat('F');
    $daysInMonth = $startOfMonth->daysInMonth;
    $firstDayOfWeek = $startOfMonth->dayOfWeek;
    $offset = ($firstDayOfWeek + 1) % 7;

    $gridCells = $offset + $daysInMonth;
    $trailing = (7 - ($gridCells % 7)) % 7;

    $todayKey = now()->format('Y-m-d');

    $prevMonth = $startOfMonth->copy()->subMonth();
    $nextMonth = $startOfMonth->copy()->addMonth();

    $weekDays = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
@endphp

<div class="max-w-7xl mx-auto space-y-6" x-data="{ ready: true }">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">التقويم</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $monthName }} {{ $year }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-chevron-right text-xs"></i>
                <span>الشهر السابق</span>
            </a>

            <span class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-semibold text-gray-700">
                {{ $monthName }} {{ $year }}
            </span>

            <a href="{{ route('calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
                <span>الشهر التالي</span>
                <i class="fas fa-chevron-left text-xs"></i>
            </a>

            <a href="{{ route('sessions.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                <i class="fas fa-plus-circle"></i>
                <span>إضافة جلسة</span>
            </a>
        </div>
    </div>

    <div class="hidden md:block bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-7 border-b border-gray-100">
            @foreach($weekDays as $dayName)
                <div class="px-3 py-3 text-xs font-bold text-gray-600 text-center bg-gray-50">{{ $dayName }}</div>
            @endforeach
        </div>

        @if($sessions->isEmpty())
            <div class="py-16 text-center text-gray-500">
                <i class="fas fa-calendar-xmark text-4xl text-gray-300 mb-3 block"></i>
                <p class="font-medium">لا توجد جلسات هذا الشهر</p>
            </div>
        @else
            <div class="grid grid-cols-7">
                @for($i = 0; $i < $offset; $i++)
                    <div class="min-h-[120px] border-b border-l border-gray-100 bg-gray-50 text-gray-300 p-2"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateObj = $startOfMonth->copy()->day($day);
                        $dateKey = $dateObj->format('Y-m-d');
                        $daySessions = $sessions[$dateKey] ?? collect();
                        $isToday = $dateKey === $todayKey;
                    @endphp

                    <div class="min-h-[120px] border-b border-l border-gray-100 p-2 {{ $isToday ? 'bg-blue-50 border-[#1c5bb8]' : 'bg-white' }}">
                        <div class="text-sm font-semibold text-gray-800 text-right">{{ $day }}</div>

                        <div class="mt-2 space-y-1.5">
                            @foreach($daySessions as $session)
                                @php
                                    $pillClass = match($session->status) {
                                        'done' => 'bg-green-100 text-green-800',
                                        'postponed' => 'bg-yellow-100 text-yellow-800',
                                        'cancelled' => 'bg-red-100 text-red-800 line-through',
                                        default => 'bg-blue-100 text-blue-800',
                                    };
                                @endphp
                                <a href="{{ route('sessions.show', $session) }}"
                                   class="block px-2 py-1 rounded-lg text-[11px] font-medium truncate {{ $pillClass }} hover:opacity-90 transition"
                                   title="{{ $session->court }}">
                                    {{ \Illuminate\Support\Str::limit($session->court, 12) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endfor

                @for($i = 0; $i < $trailing; $i++)
                    <div class="min-h-[120px] border-b border-l border-gray-100 bg-gray-50 text-gray-300 p-2"></div>
                @endfor
            </div>
        @endif
    </div>

    <div class="md:hidden bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-700">جلسات الشهر</h2>
        </div>

        @if($sessions->isEmpty())
            <div class="py-10 text-center text-gray-500">
                <i class="fas fa-calendar-xmark text-3xl text-gray-300 mb-2 block"></i>
                <p>لا توجد جلسات هذا الشهر</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($sessions as $date => $dateSessions)
                    <div class="p-4">
                        <p class="text-xs font-bold text-gray-500 mb-2">{{ \Carbon\Carbon::parse($date)->translatedFormat('Y-m-d l') }}</p>
                        <div class="space-y-2">
                            @foreach($dateSessions as $session)
                                @php
                                    $itemClass = match($session->status) {
                                        'done' => 'bg-green-50 border-green-100',
                                        'postponed' => 'bg-yellow-50 border-yellow-100',
                                        'cancelled' => 'bg-red-50 border-red-100',
                                        default => 'bg-blue-50 border-blue-100',
                                    };
                                @endphp
                                <a href="{{ route('sessions.show', $session) }}" class="block border rounded-xl p-3 {{ $itemClass }}">
                                    <p class="text-sm font-semibold text-gray-800 {{ $session->status === 'cancelled' ? 'line-through text-red-700' : '' }}">
                                        {{ $session->case->title ?? 'قضية' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $session->session_time ? $session->session_time->format('H:i') : '—' }} · {{ $session->court }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
