@extends('layouts.app')

@section('title', 'القضايا')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">القضايا</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة ومتابعة جميع القضايا في مكان واحد</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('cases.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                <i class="fas fa-plus-circle text-sm"></i>
                <span>إضافة قضية</span>
            </a>
        </div>
    </div>

    @php
        $activeFiltersCount = collect([
            $filters['search'] ?? null,
            $filters['status'] ?? null,
            $filters['priority'] ?? null,
            ($filters['sort'] ?? 'newest') !== 'newest' ? ($filters['sort'] ?? null) : null,
        ])->filter(fn ($value) => filled($value))->count();
    @endphp

    <form method="GET" action="{{ route('cases.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-5 space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-800">تصفية وبحث القضايا</h2>
                <p class="text-xs text-gray-500 mt-1">خصص النتائج حسب الحالة، الأولوية، والترتيب.</p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
                    <i class="fas fa-scale-balanced ml-1 text-[10px]"></i>
                    {{ $cases->total() }} قضية
                </span>
                @if($activeFiltersCount > 0)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                        <i class="fas fa-filter ml-1 text-[10px]"></i>
                        {{ $activeFiltersCount }} فلاتر مفعلة
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3 items-end">
            <div class="xl:col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1">بحث</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <i class="fas fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="رقم القضية، العنوان، المحكمة..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 pr-9 focus:bg-white focus:border-[#1c5bb8] focus:ring-[#1c5bb8] text-sm"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">الحالة</label>
                <select name="status" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-[#1c5bb8] text-sm">
                    <option value="">كل الحالات</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>نشطة</option>
                    <option value="suspended" @selected(($filters['status'] ?? '') === 'suspended')>موقوفة</option>
                    <option value="closed" @selected(($filters['status'] ?? '') === 'closed')>مغلقة</option>
                    <option value="archived" @selected(($filters['status'] ?? '') === 'archived')>أرشيف</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">الأولوية</label>
                <select name="priority" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-[#1c5bb8] text-sm">
                    <option value="">كل الأولويات</option>
                    <option value="high" @selected(($filters['priority'] ?? '') === 'high')>عالية</option>
                    <option value="medium" @selected(($filters['priority'] ?? '') === 'medium')>متوسطة</option>
                    <option value="low" @selected(($filters['priority'] ?? '') === 'low')>منخفضة</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">الترتيب</label>
                <select name="sort" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-[#1c5bb8] text-sm">
                    <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>الأحدث</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>الأقدم</option>
                    <option value="next_session" @selected(($filters['sort'] ?? '') === 'next_session')>أقرب جلسة</option>
                    <option value="fees_high" @selected(($filters['sort'] ?? '') === 'fees_high')>الأتعاب الأعلى</option>
                    <option value="fees_low" @selected(($filters['sort'] ?? '') === 'fees_low')>الأتعاب الأقل</option>
                </select>
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0D1F4E] text-white hover:bg-[#0A1628] transition text-sm font-medium shadow-sm">
                <i class="fas fa-filter text-xs"></i>
                <span>تطبيق الفلاتر</span>
            </button>
            <a href="{{ route('cases.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition text-sm">
                <i class="fas fa-rotate-left text-xs"></i>
                <span>إعادة تعيين</span>
            </a>
        </div>
    </form>

    <!-- Table View -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">رقم القضية</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموكّل</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">المحكمة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">النوع</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">الأولوية</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">الأتعاب</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($cases as $case)
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $case->case_number }}</td>
                            <td class="px-4 py-3 max-w-[200px]">
                                <div class="truncate text-gray-700" title="{{ $case->title }}">{{ $case->title }}</div>
                            </td>
                            <td class="px-4 py-3 max-w-[160px]">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($case->clients->take(2) as $client)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 whitespace-nowrap">
                                            {{ $client->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endforelse
                                    @if($case->clients->count() > 2)
                                        <span class="text-xs text-gray-400">+{{ $case->clients->count() - 2 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 max-w-[130px]">
                                <div class="truncate" title="{{ $case->court }}">{{ $case->court }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $case->case_type ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($case->status == 'active')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">نشطة</span>
                                @elseif($case->status == 'suspended')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium">موقوفة</span>
                                @elseif($case->status == 'closed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">مغلقة</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600 font-medium">أرشيف</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($case->priority == 'low')
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500">منخفضة</span>
                                @elseif($case->priority == 'medium')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-50 text-blue-700">متوسطة</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-50 text-red-700 font-medium">عالية</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-gray-800 font-medium">{{ number_format($case->fees_total, 0) }} <span class="text-gray-400 font-normal text-xs">دج</span></div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    مدفوع: <span class="text-green-600">{{ number_format($case->fees_paid, 0) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('cases.show', $case) }}" title="عرض" class="text-gray-400 hover:text-[#1c5bb8] transition"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('cases.edit', $case) }}" title="تعديل" class="text-gray-400 hover:text-[#1c5bb8] transition"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('cases.destroy', $case) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه القضية؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="حذف" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-scale-balanced text-5xl text-gray-300 mb-3 block"></i>
                                <p>لا توجد قضايا مسجلة</p>
                                <a href="{{ route('cases.create') }}" class="mt-2 inline-block text-sm text-[#1c5bb8] hover:underline">إضافة قضية جديدة</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $cases->links() }}
    </div>
</div>
@endsection