<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = ['name' => $request->name];

        if ($request->filled('cropped_photo')) {
            $image_parts = explode(";base64,", $request->cropped_photo);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'profile-photos/' . uniqid() . '.jpg';
                
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
                }

                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
                $data['profile_photo_path'] = $fileName;
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
