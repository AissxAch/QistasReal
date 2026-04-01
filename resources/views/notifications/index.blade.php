@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">الإشعارات</h1>
            <p class="text-sm text-gray-500 mt-1">جميع الإشعارات الخاصة بحسابك</p>
        </div>
        <form method="POST" action="{{ route('notifications.mark-read') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-check-double"></i>
                <span>تحديد الكل كمقروء</span>
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <div class="p-5 {{ $notification->isRead() ? 'bg-white' : 'bg-blue-50/30' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $notification->title }}</p>
                            @if($notification->body)
                                <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($notification->body, 120) }}</p>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-4xl text-gray-300 mb-2 block"></i>
                    لا توجد إشعارات
                </div>
            @endforelse
        </div>
    </div>

    <div>
        {{ $notifications->links() }}
    </div>
</div>
@endsection
