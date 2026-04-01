<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4 text-sm text-slate-700 leading-7">
            <p class="font-extrabold text-slate-900 mb-2">تأكيد البريد الإلكتروني مطلوب لإكمال تفعيل حسابك.</p>
            <p>إذا كنت قد أنشأت الحساب بنفسك، يرجى فتح رسالة البريد الإلكتروني والضغط على رابط التأكيد قبل المتابعة.</p>
            <p class="mt-2 text-slate-600">أما إذا تمّت دعوتك من طرف مالك المكتب، فسيتم تفعيل حسابك تلقائيًا عند إكمال تعيين كلمة المرور من رابط الدعوة.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 font-medium text-sm text-emerald-700">
                تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        إعادة إرسال رسالة التحقق
                    </x-button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-slate-600 hover:text-[#1c5bb8] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1c5bb8]/40"
                >
                    تعديل الملف الشخصي</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-slate-600 hover:text-[#1c5bb8] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1c5bb8]/40 ms-2">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
