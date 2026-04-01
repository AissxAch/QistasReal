@extends('layouts.app')

@section('title', 'حالة الوصول')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">الوصول مقفول مؤقتًا</h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 leading-7">
                        @if($reason === 'no_firm')
                            حسابك غير مرتبط حاليًا بأي مكتب قانوني، لذلك لا يمكنك الدخول إلى الأقسام التشغيلية.
                        @elseif($reason === 'no_subscription')
                            مكتبك لا يملك اشتراكًا نشطًا حاليًا، لذلك تم إيقاف الوصول إلى الأقسام التشغيلية.
                        @else
                            اشتراك المكتب الحالي غير نشط (منتهي أو موقوف)، لذلك تم تقييد الوصول إلى الأقسام التشغيلية.
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800 leading-7">
                @if($reason === 'no_firm')
                    @if(auth()->user()->isOwner())
                        إذا كنت تريد البدء بخطة <span class="font-bold">الأساسي</span>، ابدأ من صفحة الاشتراك ثم أكمل تفعيل المكتب والاشتراك.
                    @else
                        إذا كنت تنتمي إلى مكتب قائم، تواصل مع مالك المكتب ليقوم بدعوتك وربط حسابك بالمكتب الصحيح.
                        <br>
                        وإذا كنت تريد فتح مكتبك الخاص بخطة <span class="font-bold">الأساسي</span>، استخدم حساب مالك مكتب أو تواصل مع الإدارة لتفعيل البداية.
                    @endif
                @else
                    @if(auth()->user()->isOwner())
                        بصفتك مالك المكتب، يمكنك فتح صفحة الاشتراك لشراء/تجديد الاشتراك ثم العودة للعمل.
                    @else
                        تواصل مع مالك المكتب ليقوم بشراء أو تجديد الاشتراك.
                    @endif
                    @if($owner)
                        <div class="mt-2 font-semibold">
                            جهة التواصل: {{ $owner->name }}
                            @if($owner->email)
                                — {{ $owner->email }}
                            @endif
                            @if($owner->phone)
                                — {{ $owner->phone }}
                            @endif
                        </div>
                    @endif
                @endif
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                @if(auth()->user()->isOwner())
                    <a href="{{ route('subscription') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#1c5bb8] hover:bg-[#164a97] text-white text-sm font-semibold px-4 py-2.5 transition">
                        <i class="fas fa-credit-card"></i>
                        @if($reason === 'no_firm')
                            البدء بخطة الأساسي
                        @else
                            إدارة الاشتراك
                        @endif
                    </a>
                @endif

                @if(!auth()->user()->isOwner() && $reason === 'no_firm')
                    <a href="https://wa.me/213791036692" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg border border-blue-300 text-blue-700 hover:bg-blue-50 text-sm font-semibold px-4 py-2.5 transition">
                        <i class="fas fa-comments"></i>
                        تواصل مع الدعم لبدء اشتراك أساسي
                    </a>
                    <a href="mailto:admin@qistas.com" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold px-4 py-2.5 transition">
                        <i class="fas fa-envelope"></i>
                        admin@qistas.com
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold px-4 py-2.5 transition">
                        <i class="fas fa-sign-out-alt"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
