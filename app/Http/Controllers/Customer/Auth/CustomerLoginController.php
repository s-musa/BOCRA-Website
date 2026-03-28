<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CustomerLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        
        return Inertia::render('Customer/Auth/Login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // Can be email, username, or phone
            'password' => 'required|string',
            'remember' => 'nullable|boolean'
        ]);
        
        $loginField = $this->getLoginField($request->login);
        
        $credentials = [
            $loginField => $request->login,
            'password' => $request->password
        ];
        
        $remember = $request->filled('remember');
        
        // Attempt login
        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('customer')->user()->name . '!');
        }
        
        throw ValidationException::withMessages([
            'login' => ['Invalid credentials. Please check your email/username and password.'],
        ]);
    }
    
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('customer.login')
            ->with('success', 'You have been logged out successfully.');
    }
   
    private function getLoginField($login)
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        if (preg_match('/^[0-9]{10,15}$/', $login)) {
            return 'phone';
        }
        
        return 'username';
    }
}
