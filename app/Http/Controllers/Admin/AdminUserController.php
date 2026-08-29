<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $totalCollectors = User::where('role', 'waste_collector')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalGeneralUsers = User::where('role', 'user')->count();
        
        return view('admin.users', compact('users', 'totalCollectors', 'totalAdmins', 'totalGeneralUsers'));
    }

    public function createUser()
    {
        return view('admin.create_user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:50'],
            'road' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user,waste_collector'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'sector' => $request->sector,
            'road' => $request->road,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'admin') {
            \App\Models\AdminProfile::create([
                'user_id' => $user->id,
                'department' => 'Waste Management Operations',
                'access_level' => 'Standard Admin',
            ]);
        } elseif ($user->role === 'waste_collector') {
            \App\Models\CollectorProfile::create([
                'user_id' => $user->id,
                'assigned_vehicle' => 'Truck #' . rand(100, 999),
                'shift_preference' => 'Morning Shift',
            ]);
        }

        return redirect()->route('admin.users')->with('status', 'User created successfully');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:50'],
            'road' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:admin,user,waste_collector'],
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address', 'sector', 'road', 'role']));

        return redirect()->route('admin.users')->with('status', 'User updated successfully');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'User deleted successfully');
    }
}
