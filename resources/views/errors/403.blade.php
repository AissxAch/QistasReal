<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>غير مصرح - قسطاس</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
    <div class="w-full max-w-xl rounded-3xl border border-red-100 bg-white shadow-sm p-8 text-center">
        <div class="mx-auto w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mb-4">
            <span class="text-2xl font-black">!</span>
        </div>

        <p class="text-sm font-bold text-red-600">403</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-2">لا تملك صلاحية الوصول</h1>

        <p class="text-slate-600 mt-3 leading-7">
            {{ $exception->getMessage() ?: 'عذرًا، ليس لديك الإذن الكافي لتنفيذ هذه العملية.' }}
        </p>

        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                رجوع
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-[#1c5bb8] text-white text-sm font-semibold hover:bg-[#174a95] transition">
                العودة للوحة التحكم
            </a>
        </div>
    </div>
</body>
</html>
