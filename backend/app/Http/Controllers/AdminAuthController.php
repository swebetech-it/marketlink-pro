<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && (Auth::user()->role === 'ADMIN' || session('admin_logged_in') === true)) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('login'));
        $passwordInput = $request->input('password');

        // Look up admin by exact email or phone
        $user = User::where('email', $loginInput)
            ->orWhere('phone', $loginInput)
            ->orWhere('phone', '+' . ltrim($loginInput, '+'))
            ->first();

        if ($user && $user->role === 'ADMIN') {
            $passwordMatches = false;

            if ($user->password && Hash::check($passwordInput, $user->password)) {
                $passwordMatches = true;
            }

            if ($passwordMatches) {
                Auth::login($user, $request->boolean('remember'));
                session([
                    'admin_logged_in' => true,
                    'admin_user_id' => $user->id,
                    'admin_name' => $user->full_name,
                ]);

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Karibu tena! Umeingia kikamilifu kama Msimamizi Mkuu.');
            }
        }

        return back()->withErrors([
            'login' => 'Namba ya simu/barua pepe au nenosiri si sahihi, au akaunti hii haina mamlaka ya Msimamizi (SuperAdmin). Tafadhali thibitisha taarifa zako.',
        ])->withInput($request->only('login', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Umetoka kwenye mfumo wa usimamizi kwa usalama.');
    }
}