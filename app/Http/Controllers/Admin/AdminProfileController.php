<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,avif,webp|max:5120',
        ]);

        $data = $request->only(['phone', 'address']);
        $data['name'] = trim($request->first_name . ' ' . $request->last_name);

        if ($request->hasFile('profile_image')) {
            Log::info('Admin profile image found in request', ['filename' => $request->file('profile_image')->getClientOriginalName()]);
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile_images', 'public');
            Log::info('Admin profile image stored', ['path' => $path]);
            $data['profile_image'] = $path;
        } else {
            Log::info('No admin profile image in request');
        }

        $user->fill($data);
        $user->save();

        return redirect()->back()->with('success', 'Admin profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Admin password updated successfully!');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->update(['profile_image' => null]);
            return redirect()->back()->with('success', 'Avatar deleted successfully!');
        }
        return redirect()->back()->with('error', 'No avatar to delete.');
    }
}
