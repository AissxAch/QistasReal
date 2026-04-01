@extends('layouts.app')

@section('title', 'العملاء')

@section('content')
<div x-data="clientsIndex()" x-init="init()" class="max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">العملاء</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة ومتابعة جميع العملاء في مكان واحد</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" action="{{ route('clients.index') }}" class="flex gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم / الهاتف / البريد"
                           class="w-64 py-2 pr-10 pl-3 rounded-xl border-gray-200 focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition">بحث</button>
                @if(request('search'))
                    <a href="{{ route('clients.index') }}" class="px-4 py-2 rounded-xl text-gray-500 hover:text-gray-700 transition">إعادة ضبط</a>
                @endif
            </form>
            <a href="{{ route('clients.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-gradient-to-r from-[#1c5bb8] to-[#2a6dc9] text-white hover:from-[#0f2d62] hover:to-[#1c5bb8] transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus-circle text-sm"></i>
                <span>إضافة عميل</span>
            </a>
        </div>
    </div>

    <!-- View Toggle & Results Count -->
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500 bg-gray-50 px-4 py-2 rounded-full">عرض <span>{{ $clients->firstItem() ?? 0 }}</span> – <span>{{ $clients->lastItem() ?? 0 }}</span> من <span>{{ $clients->total() }}</span> عميل</p>
        <div class="flex gap-2 bg-gray-100 p-1 rounded-xl">
            <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white shadow-sm text-[#1c5bb8]' : 'text-gray-500'"
                    class="px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                <i class="fas fa-table"></i>
            </button>
            <button @click="viewMode = 'card'" :class="viewMode === 'card' ? 'bg-white shadow-sm text-[#1c5bb8]' : 'text-gray-500'"
                    class="px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                <i class="fas fa-th-large"></i>
            </button>
        </div>
    </div>

    <!-- Table View -->
    <div x-show="viewMode === 'table'" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الهاتف</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">عدد القضايا</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">آخر نشاط</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">إجراءات</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                @forelse($clients as $client)
                    <tr class="group hover:bg-gray-50/80 transition-all duration-200 cursor-pointer" onclick="window.location='{{ route('clients.show', $client) }}'">
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">{{ $client->name }}</td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700" dir="ltr">{{ $client->phone ?? '—' }}</td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700" dir="ltr">{{ $client->email ?? '—' }}</td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                <i class="fas fa-gavel text-xs"></i> {{ $client->cases_count ?? $client->cases->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500">{{ $client->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500" onclick="event.stopPropagation()">
                            <div class="flex gap-2">
                                <a href="{{ route('clients.edit', $client) }}" class="p-2 rounded-lg text-gray-400 hover:text-[#1c5bb8] hover:bg-gray-100 transition-all duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-200">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-users text-6xl text-gray-200 mb-4"></i>
                                <p class="text-gray-500 text-lg mb-2">لا يوجد عملاء</p>
                                <a href="{{ route('clients.create') }}" class="mt-2 inline-block text-sm text-[#1c5bb8] hover:underline font-medium">إضافة عميل جديد</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card View -->
    <div x-show="viewMode === 'card'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($clients as $client)
            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 overflow-hidden cursor-pointer group" onclick="window.location='{{ route('clients.show', $client) }}'">
                <div class="p-5">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1c5bb8] to-[#c9a227] flex items-center justify-center text-white font-bold text-xl shadow-md">
                            {{ strtoupper(substr($client->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#1c5bb8] transition-colors">{{ $client->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1" dir="ltr">{{ $client->phone ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600 mb-3">
                        <div class="flex items-center gap-2"><i class="fas fa-envelope w-5 text-gray-400"></i> <span dir="ltr">{{ $client->email ?? '—' }}</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-gavel w-5 text-gray-400"></i> <span>عدد القضايا: {{ $client->cases_count ?? $client->cases->count() }}</span></div>
                    </div>
                    <div class="border-t border-gray-100 pt-3 flex justify-end gap-2" onclick="event.stopPropagation()">
                        <a href="{{ route('clients.edit', $client) }}" class="p-2 rounded-lg text-gray-400 hover:text-[#1c5bb8] hover:bg-gray-100 transition-all"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <i class="fas fa-users text-6xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-500 text-lg mb-2">لا يوجد عملاء</p>
                <a href="{{ route('clients.create') }}" class="mt-2 inline-block text-sm text-[#1c5bb8] hover:underline font-medium">إضافة عميل جديد</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
        <div class="text-sm text-gray-500">
            عرض <span>{{ $clients->firstItem() ?? 0 }}</span> – <span>{{ $clients->lastItem() ?? 0 }}</span> من <span>{{ $clients->total() }}</span>
        </div>
        <div class="flex gap-2">
            {{ $clients->links() }}
        </div>
    </div>
</div>

<script>
    function clientsIndex() {
        return {
            viewMode: localStorage.getItem('clientsViewMode') || 'table',
            init() {
                this.$watch('viewMode', value => localStorage.setItem('clientsViewMode', value));
            }
        }
    }
</script>
@endsection