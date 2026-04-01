<div class="space-y-4">
    <p class="text-sm text-gray-600">
        إدارة وتسجيل الخروج من جلساتك النشطة على المتصفحات والأجهزة الأخرى. إذا لزم الأمر، يمكنك تسجيل الخروج من جميع جلساتك الأخرى عبر جميع أجهزتك. بعض جلساتك الأخيرة مدرجة أدناه؛ ومع ذلك، قد لا تكون هذه القائمة شاملة. إذا شعرت أن حسابك قد تم اختراقه، يجب عليك أيضًا تحديث كلمة المرور الخاصة بك.
    </p>

    @if (count($this->sessions) > 0)
        <div class="space-y-4">
            @foreach ($this->sessions as $session)
                <div class="flex items-center p-4 bg-gray-50 rounded-xl border">
                    <div class="flex-shrink-0">
                        @if ($session->agent->isDesktop())
                            <i class="fas fa-desktop text-2xl text-gray-500"></i>
                        @else
                            <i class="fas fa-mobile-alt text-2xl text-gray-500"></i>
                        @endif
                    </div>

                    <div class="mr-4 flex-1">
                        <div class="text-sm font-medium text-gray-800">
                            {{ $session->agent->platform() ? $session->agent->platform() : 'غير معروف' }} - {{ $session->agent->browser() ? $session->agent->browser() : 'غير معروف' }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $session->ip_address }},
                            @if ($session->is_current_device)
                                <span class="text-green-600 font-semibold">هذا الجهاز</span>
                            @else
                                آخر نشاط {{ $session->last_active }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex items-center justify-between pt-4 border-t">
        <button wire:click="confirmLogout"
                class="bg-[#1c5bb8] text-white rounded-xl px-4 py-2 hover:bg-[#0f2d62] transition text-sm disabled:opacity-50">
            <i class="fas fa-sign-out-alt mr-2"></i>
            <span wire:loading.remove>تسجيل الخروج من الجلسات الأخرى</span>
            <span wire:loading>جاري التسجيل...</span>
        </button>

        <div x-data="{ show: $wire.entangle('loggedOut').live }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-green-600 text-sm">
            تم تسجيل الخروج بنجاح.
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div x-show="confirmingLogout" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" x-show="confirmingLogout">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        تسجيل الخروج من جلسات المتصفح الأخرى
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        يرجى إدخال كلمة المرور الخاصة بك لتأكيد رغبتك في تسجيل الخروج من جلسات المتصفح الأخرى عبر جميع أجهزتك.
                    </p>

                    <input type="password" wire:model="password" wire:keydown.enter="logoutOtherBrowserSessions"
                           placeholder="كلمة المرور" autocomplete="current-password"
                           class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2">
                    <button wire:click="$set('confirmingLogout', false)"
                            class="bg-gray-500 text-white rounded-xl px-4 py-2 hover:bg-gray-600 transition text-sm">
                        إلغاء
                    </button>
                    <button wire:click="logoutOtherBrowserSessions"
                            class="bg-[#1c5bb8] text-white rounded-xl px-4 py-2 hover:bg-[#0f2d62] transition text-sm">
                        تسجيل الخروج
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
