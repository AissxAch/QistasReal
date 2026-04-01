<form wire:submit.prevent="updatePassword" class="space-y-5">
    {{-- Current Password --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">كلمة المرور الحالية <span class="text-red-500">*</span></label>
        <input type="password" wire:model="state.current_password" required autocomplete="current-password"
            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- New Password --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">كلمة المرور الجديدة <span class="text-red-500">*</span></label>
        <input type="password" wire:model="state.password" required autocomplete="new-password"
            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Confirm Password --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
        <input type="password" wire:model="state.password_confirmation" required autocomplete="new-password"
            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
        @error('password_confirmation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end">
        <button type="submit"
                class="bg-[#1c5bb8] text-white rounded-xl px-6 py-2.5 hover:bg-[#0f2d62] transition shadow-sm flex items-center gap-2 disabled:opacity-50">
            <i class="fas fa-key"></i>
            <span wire:loading.remove>تغيير كلمة المرور</span>
            <span wire:loading>جاري التغيير...</span>
        </button>
    </div>

    {{-- Success Message --}}
    <div x-data="{ show: $wire.entangle('saved').live }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-green-600 text-sm">
        تم تغيير كلمة المرور بنجاح.
    </div>
</form>
