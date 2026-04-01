<div class="space-y-4">
    {{-- Status --}}
    <div class="p-4 rounded-xl {{ $this->enabled ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
        <h3 class="text-lg font-semibold {{ $this->enabled ? 'text-green-800' : 'text-gray-800' }}">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    إنهاء تفعيل المصادقة الثنائية
                @else
                    المصادقة الثنائية مفعلة
                @endif
            @else
                المصادقة الثنائية غير مفعلة
            @endif
        </h3>
        <p class="text-sm text-gray-600 mt-2">
            عند تفعيل المصادقة الثنائية، ستحتاج إلى رمز أمان عشوائي أثناء تسجيل الدخول. يمكنك الحصول على هذا الرمز من تطبيق Google Authenticator على هاتفك.
        </p>
    </div>

    {{-- QR Code Section --}}
    @if ($this->enabled && $showingQrCode)
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <p class="font-semibold text-blue-800 mb-4">
                @if ($showingConfirmation)
                    لإنهاء تفعيل المصادقة الثنائية، امسح رمز QR التالي باستخدام تطبيق المصادقة على هاتفك أو أدخل مفتاح الإعداد وأدخل رمز OTP المولد.
                @else
                    المصادقة الثنائية مفعلة الآن. امسح رمز QR التالي باستخدام تطبيق المصادقة على هاتفك أو أدخل مفتاح الإعداد.
                @endif
            </p>

            <div class="bg-white p-4 inline-block rounded-lg border">
                {!! $this->user->twoFactorQrCodeSvg() !!}
            </div>

            <div class="mt-4 p-3 bg-gray-100 rounded-lg font-mono text-sm">
                <strong>مفتاح الإعداد:</strong> {{ decrypt($this->user->two_factor_secret) }}
            </div>
        </div>

        @if ($showingConfirmation)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">الرمز <span class="text-red-500">*</span></label>
                <input type="text" wire:model="code" inputmode="numeric" autofocus autocomplete="one-time-code"
                    wire:keydown.enter="confirmTwoFactorAuthentication"
                    class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition max-w-xs">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        @endif
    @endif

    {{-- Recovery Codes --}}
    @if ($showingRecoveryCodes)
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
            <p class="font-semibold text-yellow-800 mb-4">
                احفظ رموز الاسترداد هذه في مدير كلمات مرور آمن. يمكن استخدامها لاستعادة الوصول إلى حسابك إذا فقدت جهاز المصادقة الثنائية.
            </p>

            <div class="grid grid-cols-2 gap-2 font-mono text-sm bg-white p-4 rounded-lg border">
                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                    <div class="p-2 bg-gray-50 rounded">{{ $code }}</div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-2">
        @if (! $this->enabled)
            <button type="button" wire:click="enableTwoFactorAuthentication"
                    class="bg-[#1c5bb8] text-white rounded-xl px-4 py-2 hover:bg-[#0f2d62] transition text-sm">
                تفعيل
            </button>
        @else
            @if ($showingRecoveryCodes)
                <button type="button" wire:click="regenerateRecoveryCodes"
                        class="bg-gray-500 text-white rounded-xl px-4 py-2 hover:bg-gray-600 transition text-sm">
                    إعادة توليد رموز الاسترداد
                </button>
            @elseif ($showingConfirmation)
                <button type="button" wire:click="confirmTwoFactorAuthentication"
                        class="bg-[#1c5bb8] text-white rounded-xl px-4 py-2 hover:bg-[#0f2d62] transition text-sm mr-2">
                    تأكيد
                </button>
                <button type="button" wire:click="disableTwoFactorAuthentication"
                        class="bg-gray-500 text-white rounded-xl px-4 py-2 hover:bg-gray-600 transition text-sm">
                    إلغاء
                </button>
            @else
                <button type="button" wire:click="showRecoveryCodes"
                        class="bg-gray-500 text-white rounded-xl px-4 py-2 hover:bg-gray-600 transition text-sm mr-2">
                    عرض رموز الاسترداد
                </button>
                <button type="button" wire:click="disableTwoFactorAuthentication"
                        class="bg-red-500 text-white rounded-xl px-4 py-2 hover:bg-red-600 transition text-sm">
                    تعطيل
                </button>
            @endif
        @endif
    </div>
</div>
