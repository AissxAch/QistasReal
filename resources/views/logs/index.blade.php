@extends('layouts.app')

@section('title', 'السجلات')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">سجلات النظام</h1>
        <p class="text-sm text-gray-500 mt-1">متابعة آخر العمليات داخل المكتب</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">العملية</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموديل</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المعرف</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $log->created_at?->timezone(config('app.display_timezone', config('app.timezone')))->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $log->user?->name ?? 'النظام' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700">{{ $log->action }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ class_basename($log->model_type) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $log->model_id ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $log->ip_address ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">لا توجد سجلات بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
