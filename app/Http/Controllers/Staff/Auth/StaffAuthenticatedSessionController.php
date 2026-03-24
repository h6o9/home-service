<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffAuthenticatedSessionController extends Controller
{
    //
	

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('staff.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(Request $request)
{
    // ✅ Validation
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Email is required',
        'password.required' => 'Password is required',
    ]);

    // ✅ Credentials
    $credentials = $request->only('email', 'password');

    // ✅ Check Staff Exists
    $staff = Staff::where('email', $request->email)->first();

    if (!$staff) {
        return back()->with([
            'message' => 'Invalid Email',
            'alert-type' => 'error'
        ]);
    }

    // ✅ Check Status
    if ($staff->status !== 'active') {
        return back()->with([
            'message' => 'Inactive account',
            'alert-type' => 'error'
        ]);
    }

    // ✅ FIXED Remember Me (IMPORTANT 🔥)
    if (Auth::guard('staff')->attempt($credentials, $request->has('remember'))) {

        $request->session()->regenerate();

        return redirect()->route('staff.dashboard')->with([
            'message' => 'Logged in successfully.',
            'alert-type' => 'success'
        ]);
    }

    // ❌ Wrong Password
    return back()->with([
        'message' => 'Invalid Password',
        'alert-type' => 'error'
    ]);
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $notification = __('Logged out successfully.');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('staff.login')->with($notification);
    }
}
