<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lawFirm = $user->lawFirm;
        return view('settings.index', compact('user', 'lawFirm'));
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
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($lawFirm->logo) {
                Storage::disk('public')->delete($lawFirm->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $lawFirm->update($validated);

        return redirect()->route('settings')->with('success', 'تم تحديث بيانات المكتب بنجاح.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        $updateData = array_intersect_key($validated, array_flip(['name', 'phone', 'specialty', 'bio']));

        $user->update($updateData);

        return redirect()->route('settings')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}