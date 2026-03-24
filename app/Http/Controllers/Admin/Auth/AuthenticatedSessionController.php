<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('destroy');
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
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
    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
    ];

    // ✅ Check Admin Exists
    $admin = Admin::where('email', $request->email)->first();

    if (!$admin) {
        return back()->with([
            'message' => 'Invalid Email',
            'alert-type' => 'error'
        ]);
    }

    // ✅ Check Status
    if ($admin->status !== 'active') {
        return back()->with([
            'message' => 'Inactive account',
            'alert-type' => 'error'
        ]);
    }

    // ✅ Attempt Login with Remember Me
    if (Auth::guard('admin')->attempt($credentials, $request->has('remember'))) {

        $notification = [
            'message' => 'Logged in successfully.',
            'alert-type' => 'success'
        ];

        // ✅ Intended URL handling
        $intendedUrl = session()->get('url.intended');

        if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
            return redirect()->intended(route('admin.dashboard'))->with($notification);
        }

        return redirect()->route('admin.dashboard')->with($notification);
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

        return redirect()->route('admin.login')->with($notification);
    }
}
