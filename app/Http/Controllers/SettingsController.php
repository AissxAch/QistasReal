<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    public function showFirm()
    {
        $user = Auth::user();
        $lawFirm = $user->lawFirm;
        return view('settings.firm', compact('user', 'lawFirm'));
    }

    public function updateFirm(Request $request)
    {
        $user = Auth::user();
        if (!$user->isOwner()) {
            abort(403, 'غير مصرح لك بتعديل بيانات المكتب.');
        }

        $lawFirm = $user->lawFirm;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'tax_number' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',  // optional file
        ]);

        // Remove logo from the validated array so we never accidentally set it to null
        // Logo upload is handled separately; only update if a new file was provided
        unset($validated['logo']);

        $logo = $request->file('logo');

        if ($logo instanceof UploadedFile && $logo->isValid() && filled($logo->getRealPath())) {
            if ($lawFirm->logo) {
                Storage::disk('public')->delete($lawFirm->logo);
            }
            $validated['logo'] = $this->storePublicUpload($logo, 'logos');
        } elseif ($request->hasFile('logo')) {
            return back()
                ->withErrors(['logo' => 'تعذر رفع الشعار. يرجى اختيار ملف صورة صالح ثم المحاولة مرة أخرى.'])
                ->withInput();
        }

        $lawFirm->update($validated);

        return redirect()->route('settings.firm')->with('success', 'تم تحديث بيانات المكتب بنجاح.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'specialty'   => 'nullable|string|max:255',
            'bio'         => 'nullable|string|max:1000',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $updateData = array_intersect_key($validated, array_flip(['name', 'phone', 'specialty', 'bio']));

        $avatar = $request->file('avatar');

        if ($avatar instanceof UploadedFile && $avatar->isValid() && filled($avatar->getRealPath())) {
            // Delete old avatar if it's a custom one (not a Gravatar / default)
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $updateData['profile_photo_path'] = $this->storePublicUpload($avatar, 'avatars');
        } elseif ($request->hasFile('avatar')) {
            return back()
                ->withErrors(['avatar' => 'تعذر رفع الصورة. يرجى اختيار ملف صورة صالح ثم المحاولة مرة أخرى.'])
                ->withInput();
        }

        $user->update($updateData);

        return redirect()->route('settings.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->forceFill(['profile_photo_path' => null])->save();
        }

        return redirect()->route('settings.profile')->with('success', 'تم حذف صورة الملف الشخصي.');
    }

    public function removeFirmLogo()
    {
        $user = Auth::user();
        if (!$user->isOwner()) {
            abort(403);
        }

        $lawFirm = $user->lawFirm;
        if ($lawFirm->logo) {
            Storage::disk('public')->delete($lawFirm->logo);
            $lawFirm->update(['logo' => null]);
        }

        return redirect()->route('settings.firm')->with('success', 'تم حذف شعار المكتب.');
    }

    protected function storePublicUpload(UploadedFile $file, string $directory): string
    {
        $targetDirectory = storage_path('app/public/' . trim($directory, '/'));

        if (!File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'bin';
        $filename = Str::uuid()->toString() . '.' . strtolower($extension);

        $file->move($targetDirectory, $filename);

        return trim($directory, '/') . '/' . $filename;
    }
}