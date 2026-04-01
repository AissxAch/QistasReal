{{-- resources/views/cases/create.blade.php --}}
@extends('layouts.app')

@section('title', 'إضافة قضية جديدة')

@section('content')
<div x-data="createCase()" x-init="init()" class="max-w-7xl mx-auto space-y-8">
    {{-- Header & Breadcrumb --}}
    <div>
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cases.index') }}" class="hover:text-[#1c5bb8] transition">القضايا</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">إضافة قضية</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">إضافة قضية جديدة</h1>
        <p class="text-sm text-gray-500 mt-1">أدخل تفاصيل القضية بدقة لتتبعها بسهولة</p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-triangle"></i>
                <strong class="font-semibold">حدثت أخطاء في الإدخال</strong>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{--
        FIX: Removed @submit.prevent="submitForm" — the x-data is on the outer <div>,
        not the <form>, so this.$el.submit() in submitForm() pointed to the div and
        threw "TypeError: this.$el.submit is not a function".
        Now the form submits normally via the type="submit" button.
        Added x-ref="caseForm" so Alpine can still target it when needed.
    --}}
    <form method="POST" action="{{ route('cases.store') }}" x-ref="caseForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Form Area --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Section 1: Basic Info --}}
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-[#1c5bb8]"></i>
                        المعلومات الأساسية
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم القضية <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-hashtag absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="case_number" x-model="form.case_number" required
                                    class="w-full pr-10 pl-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('case_number') border-red-500 @enderror"
                                    placeholder="مثال: 2024-001"
                                    value="{{ old('case_number') }}">
                            </div>
                            @error('case_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">عنوان القضية <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('title') border-red-500 @enderror"
                                placeholder="أدخل عنوان القضية"
                                value="{{ old('title') }}">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الحالة</label>
                            <select name="status"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                                <option value="active"  {{ old('status', 'active') == 'active'  ? 'selected' : '' }}>نشطة</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>مجدولة</option>
                                <option value="closed"  {{ old('status') == 'closed'  ? 'selected' : '' }}>مغلقة</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">درجة القضية</label>
                            <select name="degree"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                                <option value="first"     {{ old('degree', 'first') == 'first'     ? 'selected' : '' }}>ابتدائي</option>
                                <option value="appeal"    {{ old('degree') == 'appeal'    ? 'selected' : '' }}>استئناف</option>
                                <option value="cassation" {{ old('degree') == 'cassation' ? 'selected' : '' }}>نقض</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الأولوية</label>
                            <select name="priority"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                                <option value="low"    {{ old('priority') == 'low'    ? 'selected' : '' }}>منخفضة</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                                <option value="high"   {{ old('priority') == 'high'   ? 'selected' : '' }}>عالية</option>
                            </select>
                        </div>
                        @if(auth()->user()->isOwner())
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">إسناد القضية لمحامين</label>
                                <select name="lawyer_ids[]" multiple
                                    class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 min-h-36">
                                    @foreach($lawyersForAssignment as $lawyer)
                                        <option value="{{ $lawyer->id }}" {{ in_array($lawyer->id, old('lawyer_ids', [])) ? 'selected' : '' }}>
                                            {{ $lawyer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">يمكنك اختيار أكثر من محامٍ.</p>
                                @error('lawyer_ids')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @error('lawyer_ids.*')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Section 2: Client Info (Many-to-Many) --}}
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-user-tie text-[#1c5bb8]"></i>
                        العملاء المرتبطين
                    </h2>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">اختر العملاء</label>
                        {{-- FIX: Added x-ref="clientList" so saveNewClient can inject new checkboxes --}}
                        <div x-ref="clientList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto border border-gray-200 rounded-2xl p-3 bg-gray-50">
                            @foreach($clients as $client)
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                    <input type="checkbox" name="client_ids[]" value="{{ $client->id }}"
                                        class="rounded border-gray-300 text-[#1c5bb8] focus:ring-[#1c5bb8]"
                                        {{ in_array($client->id, old('client_ids', [])) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $client->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">يمكنك اختيار أكثر من عميل.</p>
                    </div>
                    <div class="mt-4">
                        <button type="button" @click="openClientModal"
                            class="text-[#1c5bb8] text-sm hover:underline inline-flex items-center gap-1">
                            <i class="fas fa-plus-circle ml-1"></i> إضافة عميل جديد
                        </button>
                    </div>
                    @error('client_ids')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Section 3: Court Details --}}
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-gavel text-[#1c5bb8]"></i>
                        تفاصيل المحكمة
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">اسم المحكمة <span class="text-red-500">*</span></label>
                            <input type="text" name="court" required
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('court') border-red-500 @enderror"
                                value="{{ old('court') }}">
                            @error('court')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">نوع القضية</label>
                            <input type="text" name="case_type"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200"
                                value="{{ old('case_type') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 4: Dates & Sessions --}}
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-calendar-alt text-[#1c5bb8]"></i>
                        التواريخ والجلسات
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">تاريخ بدء القضية</label>
                            <input type="date" name="start_date"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200"
                                value="{{ old('start_date') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">تاريخ الجلسة القادمة</label>
                            <input type="date" name="next_session_date"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200"
                                value="{{ old('next_session_date') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 5: Fees --}}
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-money-bill-wave text-[#1c5bb8]"></i>
                        المعلومات المالية
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">إجمالي الأتعاب (د.ج)</label>
                            <input type="number" step="0.01" min="0" name="fees_total" x-model.number="form.fees_total"
                                @input="calcRemaining()"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200"
                                value="{{ old('fees_total', 0) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">المدفوع (د.ج)</label>
                            <input type="number" step="0.01" min="0" name="fees_paid" x-model.number="form.fees_paid"
                                @input="calcRemaining()"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200"
                                value="{{ old('fees_paid', 0) }}">
                        </div>
                    </div>
                    {{--
                        FIX: fees_remaining is no longer a <input> sent to the server.
                        The controller computes it. This is just a live display for the user.
                    --}}
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl text-sm text-gray-600">
                        <i class="fas fa-calculator ml-1"></i>
                        المتبقي: <strong x-text="form.fees_remaining.toFixed(2) + ' د.ج'"></strong>
                    </div>
                </div>

                {{-- Section 6: Notes --}}
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-paperclip text-[#1c5bb8]"></i>
                        ملاحظات
                    </h2>
                    <div>
                        <textarea name="description" rows="4"
                            class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200"
                            placeholder="أضف ملاحظات حول القضية...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-gray-50/80 rounded-2xl shadow-md border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg text-gray-800 mb-5">إجراءات</h3>
                    <div class="space-y-4">
                        {{-- FIX: Plain type="submit" — no Alpine needed here --}}
                        <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#1c5bb8] to-[#2a6dc9] text-white font-semibold hover:from-[#0f2d62] hover:to-[#1c5bb8] transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> حفظ القضية
                        </button>
                        <a href="{{ route('cases.index') }}"
                            class="w-full block text-center py-3.5 rounded-xl text-gray-500 hover:text-gray-700 transition-all duration-200">
                            إلغاء
                        </a>
                    </div>
                    <div class="mt-5 pt-4 border-t border-gray-200 text-xs text-gray-400 text-center">
                        الحقول ذات <span class="text-red-500">*</span> إلزامية
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Client Modal --}}
    <div x-show="clientModalOpen" x-cloak
         class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
         @click.self="clientModalOpen = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">إضافة عميل جديد</h3>

            {{-- Loading / error state --}}
            <p x-show="clientSaving" class="text-sm text-blue-600 mb-3 flex items-center gap-2">
                <i class="fas fa-spinner fa-spin"></i> جاري الحفظ...
            </p>
            <p x-show="clientError" x-text="clientError" class="text-sm text-red-600 mb-3"></p>

            <div class="space-y-4">
                <input type="text"     x-model="newClient.name"  placeholder="الاسم *"
                    class="w-full py-3 px-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                <input type="email"    x-model="newClient.email" placeholder="البريد الإلكتروني"
                    class="w-full py-3 px-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                <input type="text"     x-model="newClient.phone" placeholder="الهاتف"
                    class="w-full py-3 px-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="clientModalOpen = false"
                    class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">إلغاء</button>
                <button type="button" @click="saveNewClient" :disabled="clientSaving"
                    class="px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-md disabled:opacity-60">حفظ</button>
            </div>
        </div>
    </div>
</div>

<script>
    function createCase() {
        return {
            form: {
                case_number: '',
                fees_total: 0,
                fees_paid: 0,
                fees_remaining: 0,
            },
            clientModalOpen: false,
            clientSaving: false,
            clientError: '',
            newClient: { name: '', email: '', phone: '' },

            init() {
                // Pre-fill case_number only if the field is empty (no old() value)
                const input = document.querySelector('input[name="case_number"]');
                if (input && !input.value) {
                    input.value = '{{ \Carbon\Carbon::now()->format('Y') }}-' + Math.floor(Math.random() * 1000);
                }
                // Sync reactive form fees with any old() values rendered in HTML
                const total = parseFloat(document.querySelector('input[name="fees_total"]')?.value) || 0;
                const paid  = parseFloat(document.querySelector('input[name="fees_paid"]')?.value)  || 0;
                this.form.fees_total     = total;
                this.form.fees_paid      = paid;
                this.form.fees_remaining = total - paid;
            },

            calcRemaining() {
                this.form.fees_remaining = (this.form.fees_total || 0) - (this.form.fees_paid || 0);
            },

            openClientModal() {
                this.newClient  = { name: '', email: '', phone: '' };
                this.clientError = '';
                this.clientModalOpen = true;
            },

            // FIX: No longer calls location.reload() — instead dynamically appends
            // the new client as a checked checkbox so form data is preserved.
            saveNewClient() {
                if (!this.newClient.name.trim()) {
                    this.clientError = 'اسم العميل مطلوب';
                    return;
                }
                this.clientSaving = true;
                this.clientError  = '';

                fetch('{{ route('clients.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.newClient)
                })
                .then(res => res.json())
                .then(data => {
                    this.clientSaving = false;
                    if (data.id) {
                        // Append new checkbox to the list and auto-check it
                        const list  = this.$refs.clientList;
                        const label = document.createElement('label');
                        label.className = 'flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-1 rounded';
                        label.innerHTML = `
                            <input type="checkbox" name="client_ids[]" value="${data.id}" checked
                                class="rounded border-gray-300 text-[#1c5bb8] focus:ring-[#1c5bb8]">
                            <span class="text-sm text-gray-700">${data.name}</span>`;
                        list.appendChild(label);
                        this.clientModalOpen = false;
                    } else {
                        this.clientError = data.message ?? 'حدث خطأ، يرجى المحاولة مجدداً';
                    }
                })
                .catch(() => {
                    this.clientSaving = false;
                    this.clientError  = 'حدث خطأ في الاتصال بالخادم';
                });
            },
        }
    }
</script>
@endsection
