{{-- resources/views/livewire/case-scanner.blade.php --}}
<div class="max-w-7xl mx-auto space-y-8" dir="rtl">

    {{-- ─── Breadcrumb ──────────────────────────────────────────────────────── --}}
    <div>
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cases.index') }}" class="hover:text-[#1c5bb8] transition">القضايا</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">استيراد قضية بالذكاء الاصطناعي</span>
        </nav>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#1c5bb8] rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-wand-magic-sparkles text-white text-base"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">استيراد قضية بالذكاء الاصطناعي</h1>
                <p class="text-sm text-gray-500">ارفع صورة أو ملف PDF لاستخراج بيانات القضية تلقائياً</p>
            </div>
        </div>
    </div>

    {{-- ─── Step indicator ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <div class="flex items-center justify-center gap-0">
            @php
                $steps = [
                    ['id' => 'upload',     'label' => 'رفع المستند',    'icon' => 'fa-cloud-arrow-up'],
                    ['id' => 'processing', 'label' => 'معالجة OCR',     'icon' => 'fa-brain'],
                    ['id' => 'review',     'label' => 'مراجعة البيانات','icon' => 'fa-magnifying-glass'],
                    ['id' => 'done',       'label' => 'حفظ القضية',     'icon' => 'fa-circle-check'],
                ];
                $order = ['upload' => 0, 'processing' => 1, 'review' => 2, 'done' => 3];
                $currentOrder = $order[$step] ?? 0;
            @endphp

            @foreach($steps as $i => $s)
                @php
                    $sOrder    = $order[$s['id']];
                    $isActive  = $s['id'] === $step;
                    $isDone    = $sOrder < $currentOrder;
                    $isUpcoming= $sOrder > $currentOrder;
                @endphp

                {{-- connector line --}}
                @if($i > 0)
                    <div class="flex-1 h-0.5 mx-1 {{ $isDone || $isActive ? 'bg-[#1c5bb8]' : 'bg-gray-200' }} transition-colors duration-500 max-w-16"></div>
                @endif

                <div class="flex flex-col items-center gap-1 min-w-[64px]">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300
                        {{ $isDone  ? 'bg-[#1c5bb8] text-white shadow-md' : '' }}
                        {{ $isActive ? 'bg-[#1c5bb8] text-white ring-4 ring-[#1c5bb8]/25 shadow-lg scale-110' : '' }}
                        {{ $isUpcoming ? 'bg-gray-100 text-gray-400' : '' }}">
                        @if($isDone)
                            <i class="fas fa-check text-xs"></i>
                        @else
                            <i class="fas {{ $s['icon'] }} text-xs"></i>
                        @endif
                    </div>
                    <span class="text-xs font-medium {{ $isActive ? 'text-[#1c5bb8]' : ($isDone ? 'text-gray-600' : 'text-gray-400') }} hidden sm:block text-center leading-tight">{{ $s['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ─── Error banner ───────────────────────────────────────────────────── --}}
    @if($errorMessage)
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-start gap-3">
            <i class="fas fa-triangle-exclamation mt-0.5 text-red-500"></i>
            <div>
                <p class="font-semibold text-sm">حدث خطأ</p>
                <p class="text-sm mt-0.5">{{ $errorMessage }}</p>
            </div>
            <button wire:click="$set('errorMessage', '')" class="mr-auto text-red-400 hover:text-red-600 transition"><i class="fas fa-xmark"></i></button>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════
         STEP A — DROPZONE
    ════════════════════════════════════════════════════════════════════════ --}}
    @if($step === 'upload')
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8"
             x-data="{
                 dragging: false,
                 previewSrc: null,
                 previewName: null,
                 previewType: null,
                 handleDrop(e) {
                     this.dragging = false;
                     const f = e.dataTransfer.files[0];
                     if (f) { this.setPreview(f); this.$refs.fileInput.files = e.dataTransfer.files; this.$refs.fileInput.dispatchEvent(new Event('change')); }
                 },
                 handleChange(e) {
                     const f = e.target.files[0];
                     if (f) this.setPreview(f);
                 },
                 setPreview(f) {
                     this.previewName = f.name;
                     this.previewType = f.type;
                     if (f.type.startsWith('image/')) {
                         const r = new FileReader();
                         r.onload = ev => this.previewSrc = ev.target.result;
                         r.readAsDataURL(f);
                     } else {
                         this.previewSrc = null;
                     }
                 },
                 clearFile() {
                     this.previewSrc = null; this.previewName = null; this.previewType = null;
                     this.$refs.fileInput.value = '';
                     @this.set('file', null);
                 }
             }">

            <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-cloud-arrow-up text-[#1c5bb8]"></i>
                رفع مستند القضية
            </h2>

            {{-- Dropzone --}}
            <div class="relative"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="handleDrop($event)">

                <label for="ocr-file-input"
                       :class="dragging ? 'border-[#1c5bb8] bg-[#1c5bb8]/5 scale-[1.01]' : 'border-gray-300 bg-gray-50 hover:border-[#1c5bb8] hover:bg-[#1c5bb8]/5'"
                       class="flex flex-col items-center justify-center w-full min-h-64 border-2 border-dashed rounded-2xl cursor-pointer transition-all duration-300 p-8 text-center group">

                    <template x-if="!previewName">
                        <div class="space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-[#1c5bb8]/10 flex items-center justify-center group-hover:bg-[#1c5bb8]/20 transition-colors duration-300">
                                <i class="fas fa-file-arrow-up text-3xl text-[#1c5bb8]"></i>
                            </div>
                            <div>
                                <p class="text-base font-semibold text-gray-700">اسحب المستند وأفلته هنا</p>
                                <p class="text-sm text-gray-400 mt-1">أو <span class="text-[#1c5bb8] font-medium underline">تصفح الملفات</span></p>
                            </div>
                            <p class="text-xs text-gray-400">صور JPG / PNG أو ملف PDF — حتى 20 ميجابايت</p>
                        </div>
                    </template>

                    {{-- File preview (image) --}}
                    <template x-if="previewName && previewSrc">
                        <div class="space-y-3 w-full">
                            <img :src="previewSrc" alt="معاينة" class="max-h-72 mx-auto rounded-xl shadow-md object-contain">
                            <p class="text-sm font-medium text-gray-700" x-text="previewName"></p>
                            <p class="text-xs text-[#1c5bb8]">انقر لتغيير الملف</p>
                        </div>
                    </template>

                    {{-- File preview (PDF) --}}
                    <template x-if="previewName && !previewSrc">
                        <div class="space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-red-50 flex items-center justify-center">
                                <i class="fas fa-file-pdf text-3xl text-red-500"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700" x-text="previewName"></p>
                            <p class="text-xs text-[#1c5bb8]">انقر لتغيير الملف</p>
                        </div>
                    </template>
                </label>

                <input id="ocr-file-input"
                       x-ref="fileInput"
                       type="file"
                       wire:model="file"
                       @change="handleChange($event)"
                       accept=".jpg,.jpeg,.png,.pdf"
                       class="hidden">
            </div>

            {{-- Validation error --}}
            @error('file')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                    <i class="fas fa-circle-exclamation text-xs"></i> {{ $message }}
                </p>
            @enderror

            {{-- Loading progress while Livewire uploads the temp file --}}
            <div wire:loading wire:target="file" class="mt-3 flex items-center gap-2 text-sm text-[#1c5bb8]">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                جارٍ رفع الملف…
            </div>

            {{-- Tips --}}
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                    ['icon' => 'fa-file-image',    'color' => 'blue',  'title' => 'صور عالية الجودة', 'desc' => 'استخدم دقة 300 dpi أو أعلى للحصول على نتائج أفضل'],
                    ['icon' => 'fa-file-pdf',       'color' => 'red',   'title' => 'ملفات PDF',         'desc' => 'ملفات PDF المسحوحة ضوئياً مدعومة بالكامل'],
                    ['icon' => 'fa-pencil',         'color' => 'amber', 'title' => 'مراجعة يدوية',      'desc' => 'يمكنك تصحيح أي حقل قبل الحفظ'],
                ] as $tip)
                    <div class="flex items-start gap-3 bg-gray-50 rounded-xl p-3">
                        <div class="w-8 h-8 rounded-lg bg-{{ $tip['color'] }}-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $tip['icon'] }} text-{{ $tip['color'] }}-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700">{{ $tip['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $tip['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Upload button --}}
            <div class="mt-6 flex justify-end">
                <button wire:click="upload"
                        wire:loading.attr="disabled"
                        wire:target="upload,file"
                        x-bind:disabled="!previewName"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#1c5bb8] text-white font-semibold hover:bg-[#0f2d62] active:scale-95 transition-all duration-200 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="upload">
                        <i class="fas fa-wand-magic-sparkles ml-1"></i>
                        بدء الاستخراج الذكي
                    </span>
                    <span wire:loading wire:target="upload" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        جارٍ المعالجة…
                    </span>
                </button>
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════
         STEP B — PROCESSING (AI queue job running)
    ════════════════════════════════════════════════════════════════════════ --}}
    @if($step === 'processing')
        {{-- Poll every 2 s to check job status --}}
        <div wire:poll.2000ms="checkJobStatus"></div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
            {{-- Animated brain icon --}}
            <div class="relative w-24 h-24 mx-auto mb-6">
                {{-- Outer ring --}}
                <div class="absolute inset-0 rounded-full border-4 border-[#1c5bb8]/20"></div>
                {{-- Spinning ring --}}
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-[#1c5bb8] animate-spin"></div>
                {{-- Inner icon --}}
                <div class="absolute inset-3 rounded-full bg-[#1c5bb8]/10 flex items-center justify-center">
                    <i class="fas fa-brain text-2xl text-[#1c5bb8]"></i>
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">الذكاء الاصطناعي يحلل المستند</h2>
            <p class="text-gray-500 text-sm mb-8">يتم استخراج بيانات القضية من الوثيقة، قد تستغرق العملية بضع ثوانٍ</p>

            {{-- Animated progress bar --}}
            <div class="max-w-md mx-auto">
                <div class="flex justify-between text-xs text-gray-400 mb-2">
                    <span>معالجة OCR</span>
                    <span>{{ min(95, $pollCount * 3 + 5) }}%</span>
                </div>
                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-l from-[#1c5bb8] to-[#4f8ef7] rounded-full transition-all duration-[1500ms] ease-out animate-pulse"
                         style="width: {{ min(95, $pollCount * 3 + 5) }}%"></div>
                </div>
            </div>

            {{-- Animated stage labels --}}
            <div class="mt-8 grid grid-cols-3 gap-4 max-w-md mx-auto text-xs text-gray-400">
                @php
                    $stages = [
                        ['icon' => 'fa-file-lines', 'label' => 'قراءة المستند',   'threshold' => 0],
                        ['icon' => 'fa-language',   'label' => 'تحليل النص',      'threshold' => 10],
                        ['icon' => 'fa-table-cells','label' => 'استخراج الحقول',  'threshold' => 22],
                    ];
                @endphp
                @foreach($stages as $stage)
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center
                            {{ $pollCount >= $stage['threshold'] ? 'bg-[#1c5bb8]/10 text-[#1c5bb8]' : 'bg-gray-100 text-gray-300' }}
                            transition-colors duration-700">
                            <i class="fas {{ $stage['icon'] }} text-sm"></i>
                        </div>
                        <span class="{{ $pollCount >= $stage['threshold'] ? 'text-gray-600 font-medium' : '' }}">{{ $stage['label'] }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Document preview thumbnail --}}
            @if($previewUrl)
                <div class="mt-8 inline-block">
                    <div class="relative inline-block">
                        <img src="{{ $previewUrl }}" alt="المستند" class="h-24 rounded-xl shadow-md object-cover opacity-60">
                        <div class="absolute inset-0 bg-[#1c5bb8]/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-magnifying-glass text-[#1c5bb8] animate-pulse"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">جارٍ تحليل المستند</p>
                </div>
            @endif
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════
         STEP C — SIDE-BY-SIDE REVIEW
    ════════════════════════════════════════════════════════════════════════ --}}
    @if($step === 'review')
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- ──── Left panel: Original document ──── --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="fas fa-file-lines text-[#1c5bb8]"></i>
                        المستند الأصلي
                    </h3>
                    <a href="{{ $previewUrl }}" target="_blank"
                       class="text-xs text-[#1c5bb8] hover:underline flex items-center gap-1">
                        <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                        فتح في تبويب جديد
                    </a>
                </div>
                <div class="flex-1 overflow-auto bg-gray-50 p-3 min-h-[500px]">
                    @if($previewUrl)
                        @php $ext = strtolower(pathinfo(parse_url($previewUrl, PHP_URL_PATH), PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ $previewUrl }}" alt="المستند" class="w-full rounded-xl shadow-sm object-contain">
                        @else
                            <iframe src="{{ $previewUrl }}"
                                    class="w-full h-full rounded-xl border border-gray-200"
                                    style="min-height:500px"
                                    title="معاينة PDF"></iframe>
                        @endif
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 py-16">
                            <i class="fas fa-image text-4xl mb-3"></i>
                            <span class="text-sm">لا توجد معاينة متاحة</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ──── Right panel: Extracted form ──── --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 flex flex-col">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-green-100 flex items-center justify-center">
                        <i class="fas fa-wand-magic-sparkles text-green-600 text-xs"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm">البيانات المستخرجة — راجع وصحّح</h3>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-5">

                    {{-- Validation errors --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-3 text-sm">
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- AI confidence notice --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2">
                        <i class="fas fa-circle-info text-amber-500 mt-0.5 text-sm"></i>
                        <p class="text-xs text-amber-700">تم استخراج البيانات تلقائياً. يُرجى مراجعة الحقول وتصحيح أيٍّ منها قبل الحفظ.</p>
                    </div>

                    {{-- ── Section: Basic Info ── --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                            <i class="fas fa-info-circle text-[#1c5bb8]"></i>
                            المعلومات الأساسية
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- رقم القضية --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">رقم القضية <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="case_number"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all @error('case_number') border-red-400 @enderror">
                                @error('case_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            {{-- عنوان القضية --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">عنوان القضية</label>
                                <input type="text" wire:model="title"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                            </div>
                            {{-- المحكمة --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">المحكمة <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="court"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all @error('court') border-red-400 @enderror">
                                @error('court') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            {{-- نوع القضية --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">نوع القضية</label>
                                <select wire:model="case_type"
                                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                                    <option value="">— اختر —</option>
                                    @foreach(['مدنية','جزائية','تجارية','إدارية','أحوال شخصية','عمالية','أخرى'] as $t)
                                        <option value="{{ $t }}" @selected($case_type === $t)>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- الدرجة --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">الدرجة <span class="text-red-500">*</span></label>
                                <select wire:model="degree"
                                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                                    @foreach(['ابتدائي','استئناف','نقض','تنفيذ'] as $d)
                                        <option value="{{ $d }}" @selected($degree === $d)>{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- الأولوية --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">الأولوية</label>
                                <select wire:model="priority"
                                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                                    <option value="high" @selected($priority === 'high')>عالية</option>
                                    <option value="medium" @selected($priority === 'medium')>متوسطة</option>
                                    <option value="low" @selected($priority === 'low')>منخفضة</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ── Section: Dates ── --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                            <i class="fas fa-calendar text-[#1c5bb8]"></i>
                            التواريخ
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">تاريخ البدء</label>
                                <input type="date" wire:model="start_date"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">الجلسة القادمة</label>
                                <input type="date" wire:model="next_session_date"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- ── Section: Fees ── --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                            <i class="fas fa-coins text-[#1c5bb8]"></i>
                            الأتعاب
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">إجمالي الأتعاب</label>
                                <input type="number" step="0.01" min="0" wire:model="fees_total"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">المدفوع</label>
                                <input type="number" step="0.01" min="0" wire:model="fees_paid"
                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all">
                            </div>
                        </div>
                        {{-- Remaining (computed preview) --}}
                        <div class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-equals text-gray-400"></i>
                            المتبقي:
                            <span class="font-semibold text-gray-700">{{ number_format(max(0, (float)$fees_total - (float)$fees_paid), 2) }}</span>
                        </div>
                    </div>

                    {{-- ── Section: Description ── --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                            <i class="fas fa-align-right text-[#1c5bb8]"></i>
                            وصف القضية
                        </h4>
                        <textarea wire:model="description" rows="4"
                                  placeholder="ملخص أو ملاحظات حول القضية…"
                                  class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 text-sm transition-all resize-none"></textarea>
                    </div>

                </div>

                {{-- ── Action footer ── --}}
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between gap-3">
                    <button wire:click="resetScanner"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 transition-all">
                        <i class="fas fa-rotate-right text-xs"></i>
                        مسح والبدء من جديد
                    </button>

                    <button wire:click="saveCase"
                            wire:loading.attr="disabled"
                            wire:target="saveCase"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#1c5bb8] text-white font-semibold text-sm hover:bg-[#0f2d62] active:scale-95 transition-all duration-200 shadow-md disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveCase">
                            <i class="fas fa-circle-check ml-1"></i>
                            تأكيد الحفظ وإنشاء القضية
                        </span>
                        <span wire:loading wire:target="saveCase" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            جارٍ الحفظ…
                        </span>
                    </button>
                </div>
            </div>

        </div>{{-- /grid --}}
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════
         STEP D — SAVING / DONE (brief flash before redirect)
    ════════════════════════════════════════════════════════════════════════ --}}
    @if($step === 'done')
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-circle-check text-4xl text-green-500"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">تم إنشاء القضية بنجاح</h2>
            <p class="text-gray-500 text-sm">جارٍ تحويلك إلى صفحة القضية…</p>
            <div class="mt-4 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-[#1c5bb8]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </div>
        </div>
    @endif

</div>
