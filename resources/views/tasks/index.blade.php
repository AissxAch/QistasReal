@extends('layouts.app')

@section('title', 'المهام')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ mode: '{{ $view }}' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">المهام</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة ومتابعة مهام الفريق</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tasks.index', array_merge(request()->query(), ['view' => 'list'])) }}"
               @click="mode='list'"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border text-sm transition"
               :class="mode === 'list' ? 'bg-[#1c5bb8] text-white border-[#1c5bb8]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'">
                <i class="fas fa-list"></i>
            </a>
            <a href="{{ route('tasks.index', array_merge(request()->query(), ['view' => 'kanban'])) }}"
               @click="mode='kanban'"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border text-sm transition"
               :class="mode === 'kanban' ? 'bg-[#1c5bb8] text-white border-[#1c5bb8]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'">
                <i class="fas fa-table-columns"></i>
            </a>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                <i class="fas fa-plus-circle"></i>
                <span>إضافة مهمة</span>
            </a>
        </div>
    </div>

    <div x-show="mode === 'list'" x-cloak class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">العنوان</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">المرتبطة بقضية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">المسند إلى</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">الأولوية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">تاريخ الاستحقاق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $task->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($task->case)
                                    <a href="{{ route('cases.show', $task->case) }}" class="font-medium text-[#1c5bb8] hover:underline">{{ $task->case->title }}</a>
                                    @if($task->case->clients->isNotEmpty())
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            @foreach($task->case->clients->take(2) as $client)
                                                <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                                                    {{ $client->name }}
                                                </a>
                                            @endforeach
                                            @if($task->case->clients->count() > 2)
                                                <span class="text-xs text-gray-400">+{{ $task->case->clients->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($task->lawyers->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($task->lawyers as $lawyer)
                                            @if(auth()->user()?->isOwner())
                                                <a href="{{ route('team.edit', $lawyer) }}" class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                                                    {{ $lawyer->name }}
                                                </a>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700">{{ $lawyer->name }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif($task->assignedTo)
                                    <span class="font-medium">{{ $task->assignedTo->name }}</span>
                                @else
                                    غير مسندة
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->priority === 'high')
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">عالية</span>
                                @elseif($task->priority === 'medium')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">متوسطة</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">منخفضة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $task->due_date ? $task->due_date->format('Y-m-d') : '—' }}
                                @if($task->due_time)
                                    <span class="text-gray-400">• {{ optional($task->due_time)->format('H:i') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->status === 'done')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">منجزة</span>
                                @elseif($task->status === 'in_progress')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">قيد التنفيذ</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">معلقة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex gap-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-gray-400 hover:text-[#1c5bb8] transition"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-gray-400 hover:text-[#1c5bb8] transition"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-list-check text-5xl text-gray-300 mb-3 block"></i>
                                <p>لا توجد مهام حالياً</p>
                                <a href="{{ route('tasks.create') }}" class="mt-2 inline-block text-sm text-[#1c5bb8] hover:underline">إضافة مهمة جديدة</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="mode === 'kanban'" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @php
            $pendingTasks = $tasks->getCollection()->where('status', 'pending');
            $inProgressTasks = $tasks->getCollection()->where('status', 'in_progress');
            $doneTasks = $tasks->getCollection()->where('status', 'done');
        @endphp

        @php
            $kanbanColumns = [
                ['collection' => $pendingTasks,    'label' => 'معلقة',       'badge' => 'bg-yellow-100 text-yellow-700'],
                ['collection' => $inProgressTasks, 'label' => 'قيد التنفيذ', 'badge' => 'bg-blue-100 text-blue-700'],
                ['collection' => $doneTasks,        'label' => 'منجزة',       'badge' => 'bg-green-100 text-green-700'],
            ];
        @endphp

        @foreach($kanbanColumns as $col)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">{{ $col['label'] }}</h3>
                <span class="text-xs px-2 py-1 rounded-full {{ $col['badge'] }}">{{ $col['collection']->count() }}</span>
            </div>
            <div class="p-4 space-y-3 min-h-[280px]">
                @forelse($col['collection'] as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="block p-4 rounded-xl border border-gray-100 hover:border-[#1c5bb8]/30 hover:bg-blue-50/30 transition">
                        <p class="text-sm font-semibold text-gray-900">{{ $task->title }}</p>
                        <div class="mt-2 flex items-center justify-between gap-2">
                            <span class="text-xs {{ $task->priority === 'high' ? 'text-red-700 bg-red-100' : ($task->priority === 'medium' ? 'text-blue-700 bg-blue-100' : 'text-gray-700 bg-gray-100') }} px-2 py-1 rounded-full">
                                {{ $task->priority === 'high' ? 'عالية' : ($task->priority === 'medium' ? 'متوسطة' : 'منخفضة') }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $task->due_date ? $task->due_date->format('Y-m-d') : '—' }}</span>
                        </div>
                        <div class="mt-3 flex items-start gap-2">
                            <span class="w-7 h-7 rounded-full bg-[#1c5bb8]/10 text-[#1c5bb8] text-xs font-bold inline-flex items-center justify-center flex-shrink-0">
                                {{ $task->lawyers->isNotEmpty() ? mb_substr($task->lawyers->first()->name, 0, 1) : ($task->assignedTo ? mb_substr($task->assignedTo->name, 0, 1) : '؟') }}
                            </span>
                            @if($task->lawyers->isNotEmpty())
                                <span class="text-xs text-gray-600 leading-relaxed">{{ $task->lawyers->pluck('name')->join('، ') }}</span>
                            @elseif($task->assignedTo && auth()->user()?->isOwner())
                                <a href="{{ route('team.edit', $task->assignedTo) }}" class="text-xs text-[#1c5bb8] hover:underline truncate">{{ $task->assignedTo->name }}</a>
                            @else
                                <span class="text-xs text-gray-600 truncate">{{ $task->assignedTo?->name ?? 'غير مسندة' }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500">لا توجد مهام في هذا العمود.</p>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>

    <div>
        {{ $tasks->links() }}
    </div>
</div>
@endsection
