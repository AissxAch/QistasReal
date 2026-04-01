<form wire:submit.prevent="updateProfileInformation" class="space-y-5">
    {{-- Profile Photo --}}
    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div x-data="{photoName: null, photoPreview: null}">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الصورة الشخصية</label>

            {{-- Current Profile Photo --}}
            <div class="mb-4" x-show="! photoPreview">
                <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover border-2 border-gray-200">
            </div>

            {{-- New Profile Photo Preview --}}
            <div class="mb-4" x-show="photoPreview" style="display: none;">
                <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center border-2 border-gray-200"
                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <input type="file" id="photo" class="hidden"
                        wire:model.live="photo"
                        x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <div class="flex gap-2">
                <button type="button" x-on:click.prevent="$refs.photo.click()"
                        class="bg-gray-500 text-white rounded-xl px-4 py-2 hover:bg-gray-600 transition text-sm">
                    <i class="fas fa-camera mr-2"></i> اختر صورة جديدة
                </button>

                @if ($this->user->profile_photo_path)
                    <button type="button" wire:click="deleteProfilePhoto"
                            class="bg-red-500 text-white rounded-xl px-4 py-2 hover:bg-red-600 transition text-sm">
                        <i class="fas fa-trash mr-2"></i> حذف الصورة
                    </button>
                @endif
            </div>

            @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    @endif

    {{-- Name --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">الاسم الكامل <span class="text-red-500">*</span></label>
        <input type="text" wire:model="state.name" required
            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
        <input type="email" wire:model="state.email" required
            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
            <p class="text-sm mt-2 text-amber-600">
                البريد الإلكتروني غير مفعل.

                <button type="button" wire:click.prevent="sendEmailVerification"
                        class="underline text-sm text-[#1c5bb8] hover:text-[#0f2d62] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1c5bb8]">
                    إرسال رابط التفعيل مرة أخرى
                </button>
            </p>

            @if ($this->verificationLinkSent)
                <p class="mt-2 font-medium text-sm text-green-600">
                    تم إرسال رابط تفعيل جديد إلى بريدك الإلكتروني.
                </p>
            @endif
        @endif
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end">
        <button type="submit" wire:loading.attr="disabled" wire:target="photo"
                class="bg-[#1c5bb8] text-white rounded-xl px-6 py-2.5 hover:bg-[#0f2d62] transition shadow-sm flex items-center gap-2 disabled:opacity-50">
            <i class="fas fa-save"></i>
            <span wire:loading.remove wire:target="photo">حفظ</span>
            <span wire:loading wire:target="photo">جاري الحفظ...</span>
        </button>
    </div>

    {{-- Success Message --}}
    <div wire:loading.remove wire:target="photo" x-data="{ show: $wire.entangle('saved').live }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-green-600 text-sm">
        تم حفظ التغييرات بنجاح.
    </div>
</form>
