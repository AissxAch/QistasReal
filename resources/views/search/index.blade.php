@extends('layouts.app')

@section('title', 'نتائج البحث')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">نتائج البحث</h1>
            @if($query !== '')
                <p class="text-sm text-gray-500 mt-1">استعلام: <span class="font-semibold text-gray-700">{{ $query }}</span> — {{ $total }} نتيجة</p>
            @else
                <p class="text-sm text-gray-500 mt-1">اكتب كلمة بحث في الشريط العلوي للبدء.</p>
            @endif
        </div>
    </div>

    @if($query !== '' && $total === 0)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-gray-500">
            <i class="fas fa-magnifying-glass text-4xl text-gray-300 mb-3"></i>
            <p>لا توجد نتائج مطابقة.</p>
        </div>
    @endif

    @if($cases->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-scale-balanced text-[#0D1F4E]"></i>
                    القضايا
                </h2>
                <span class="text-xs text-gray-400">{{ $cases->count() }} نتيجة</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($cases as $case)
                    <a href="{{ route('cases.show', $case) }}" class="block px-5 py-4 hover:bg-gray-50 transition">
                        <div class="font-semibold text-gray-800">{{ $case->title }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $case->case_number }} • {{ $case->court }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($clients->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-users text-[#0D1F4E]"></i>
                    العملاء
                </h2>
                <span class="text-xs text-gray-400">{{ $clients->count() }} نتيجة</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($clients as $client)
                    <a href="{{ route('clients.show', $client) }}" class="block px-5 py-4 hover:bg-gray-50 transition">
                        <div class="font-semibold text-gray-800">{{ $client->name }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $client->phone ?: 'بدون هاتف' }} @if($client->email) • {{ $client->email }} @endif</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($tasks->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list-check text-[#0D1F4E]"></i>
                    المهام
                </h2>
                <span class="text-xs text-gray-400">{{ $tasks->count() }} نتيجة</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($tasks as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="block px-5 py-4 hover:bg-gray-50 transition">
                        <div class="font-semibold text-gray-800">{{ $task->title }}</div>
                        <div class="text-sm text-gray-500 mt-1">
                            {{ $task->status }}
                            @if($task->due_date)
                                • {{ $task->due_date->format('Y-m-d') }}
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
