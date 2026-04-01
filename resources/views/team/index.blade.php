@extends('layouts.app')

@section('title', 'الفريق')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">الفريق</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة أعضاء فريق العمل في المكتب</p>
            <p class="text-xs text-gray-500 mt-1">
                المقاعد المستخدمة:
                <span class="font-bold text-gray-700">{{ $seatsUsed }}</span>
                @if($seatLimit !== null)
                    / <span class="font-bold text-gray-700">{{ $seatLimit }}</span>
                @else
                    / <span class="font-bold text-gray-700">غير محدود</span>
                @endif
            </p>
        </div>
        @if(auth()->user()->isOwner())
            @if($canAddMembers)
                <a href="{{ route('team.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                    <i class="fas fa-user-plus"></i>
                    <span>إضافة عضو</span>
                </a>
            @else
                <button type="button" disabled class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-300 text-gray-600 cursor-not-allowed shadow-sm" title="تم الوصول إلى الحد الأقصى للمستخدمين في الخطة الحالية">
                    <i class="fas fa-user-plus"></i>
                    <span>إضافة عضو</span>
                </button>
            @endif
        @endif
    </div>

    @if(auth()->user()->isOwner() && !$canAddMembers)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-triangle-exclamation"></i>
                <span>تم بلوغ الحد الأقصى للمستخدمين في خطة الاشتراك الحالية. للمتابعة، قم بترقية الخطة أو حذف عضو.</span>
            </div>
        </div>
    @endif

    @if($users->count() <= 1)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center text-gray-500">
            <i class="fas fa-user-group text-5xl text-gray-300 mb-3 block"></i>
            <p class="font-medium">لا يوجد أعضاء آخرون في الفريق حالياً</p>
            <p class="text-sm mt-1">يمكنك بدء توسيع الفريق بإضافة عضو جديد.</p>
            @if(auth()->user()->isOwner() && $canAddMembers)
                <a href="{{ route('team.create') }}" class="mt-4 inline-block text-sm text-[#1c5bb8] hover:underline">إضافة أول عضو</a>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($users as $member)
            @php
                $avatarClass = match($member->role) {
                    'owner' => 'bg-blue-100 text-blue-700',
                    'lawyer' => 'bg-indigo-100 text-indigo-700',
                    default => 'bg-gray-100 text-gray-700',
                };

                $roleBadge = match($member->role) {
                    'owner' => 'bg-amber-100 text-amber-800',
                    'lawyer' => 'bg-blue-100 text-blue-800',
                    default => 'bg-gray-100 text-gray-700',
                };

                $roleLabel = match($member->role) {
                    'owner' => 'المالك',
                    'lawyer' => 'محامي',
                    default => 'مساعد',
                };

                $isPendingActivation = $member->invited_at && !$member->activated_at && $member->invitation_expires_at && $member->invitation_expires_at->isFuture();
                $isActivatedMember = (bool) $member->activated_at || !$member->invited_at;

                $statusBadge = $isPendingActivation
                    ? 'bg-amber-100 text-amber-800 border border-amber-200'
                    : 'bg-emerald-100 text-emerald-800 border border-emerald-200';

                $statusLabel = $isPendingActivation ? 'بانتظار التفعيل' : 'نشط';
                $displayTimezone = config('app.display_timezone', config('app.timezone'));

                $statusHint = $isPendingActivation
                    ? 'تنتهي الدعوة في ' . $member->invitation_expires_at->timezone($displayTimezone)->format('Y-m-d H:i')
                    : ($member->activated_at ? 'تم التفعيل في ' . $member->activated_at->timezone($displayTimezone)->format('Y-m-d H:i') : 'حساب مفعل');
            @endphp

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-12 rounded-full {{ $avatarClass }} font-bold flex items-center justify-center shrink-0">
                            {{ mb_substr($member->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-900 truncate">{{ $member->name }}</p>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <span class="inline-block px-2 py-0.5 text-xs rounded-full {{ $roleBadge }}">{{ $roleLabel }}</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full {{ $statusBadge }}">
                                    <i class="fas {{ $isPendingActivation ? 'fa-clock' : 'fa-circle-check' }} text-[10px]"></i>
                                    <span>{{ $statusLabel }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->isOwner())
                        <div class="flex items-center gap-2">
                            @if($isPendingActivation)
                                <form method="POST" action="{{ route('team.resend-invitation', $member) }}" onsubmit="return confirm('هل تريد إعادة إرسال دعوة التفعيل لهذا العضو؟')">
                                    @csrf
                                    <button type="submit" class="text-amber-500 hover:text-amber-600 transition" title="إعادة إرسال الدعوة">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('team.edit', $member) }}" class="text-gray-400 hover:text-[#1c5bb8] transition" title="تعديل">
                                <i class="fas fa-pen"></i>
                            </a>
                            @if($member->id !== auth()->id())
                                <form method="POST" action="{{ route('team.destroy', $member) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا العضو؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="حذف">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="p-5 space-y-2">
                    <p class="text-sm text-gray-500 truncate"><i class="fas fa-envelope ml-1"></i>{{ $member->email }}</p>
                    <p class="text-sm text-gray-500"><i class="fas fa-phone ml-1"></i>{{ $member->phone ?: 'لا يوجد رقم هاتف' }}</p>

                    <div class="mt-3 rounded-xl px-3 py-2 {{ $isPendingActivation ? 'bg-amber-50 border border-amber-100' : 'bg-emerald-50 border border-emerald-100' }}">
                        <p class="text-xs font-semibold {{ $isPendingActivation ? 'text-amber-700' : 'text-emerald-700' }}">حالة الحساب</p>
                        <p class="text-sm mt-1 {{ $isPendingActivation ? 'text-amber-800' : 'text-emerald-800' }}">{{ $statusHint }}</p>
                    </div>

                    @if($member->specialty)
                        <div class="pt-2">
                            <p class="text-xs text-gray-400 mb-1">التخصص</p>
                            <p class="text-sm text-gray-700">{{ $member->specialty }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
