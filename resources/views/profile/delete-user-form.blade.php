<div class="space-y-4">
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
        <p class="text-sm text-red-800">
            بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته نهائيًا. قبل حذف حسابك، يرجى تنزيل أي بيانات أو معلومات تريد الاحتفاظ بها.
        </p>
    </div>

    <div class="flex justify-start">
        <button wire:click="confirmUserDeletion"
                class="bg-red-500 text-white rounded-xl px-4 py-2 hover:bg-red-600 transition text-sm disabled:opacity-50">
            <i class="fas fa-trash mr-2"></i>
            <span wire:loading.remove>حذف الحساب</span>
            <span wire:loading>جاري الحذف...</span>
        </button>
    </div>

    {{-- Confirmation Modal --}}
    <div x-show="confirmingUserDeletion" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" x-show="confirmingUserDeletion">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-semibold text-red-800 mb-4">
                        حذف الحساب
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        هل أنت متأكد من أنك تريد حذف حسابك؟ بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته نهائيًا. يرجى إدخال كلمة المرور الخاصة بك لتأكيد رغبتك في حذف حسابك نهائيًا.
                    </p>

                    <input type="password" wire:model="password" wire:keydown.enter="deleteUser"
                           placeholder="كلمة المرور" autocomplete="current-password"
                           class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2">
                    <button wire:click="$set('confirmingUserDeletion', false)"
                            class="bg-gray-500 text-white rounded-xl px-4 py-2 hover:bg-gray-600 transition text-sm">
                        إلغاء
                    </button>
                    <button wire:click="deleteUser"
                            class="bg-red-500 text-white rounded-xl px-4 py-2 hover:bg-red-600 transition text-sm">
                        حذف الحساب
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
