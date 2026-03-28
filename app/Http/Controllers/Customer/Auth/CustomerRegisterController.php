<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class CustomerRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        
        return Inertia::render('Customer/Auth/Register');
    }
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
//            'username' => 'required|string|max:255|unique:customers,username|alpha_dash',
            'email' => 'required|email|max:255|unique:customers,email',
//            'phone' => 'nullable|string|max:20|unique:customers,phone',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        
        try {
            $customer = Customer::create([
                'name' => $validated['name'],
//                'username' => $validated['username'],
                'email' => $validated['email'],
//                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // Auto login after registration
            Auth::guard('customer')->login($customer);
            
            return redirect()->route('customer.dashboard')
                ->with('success', 'Welcome! Your account has been created successfully.');
            
        } catch (\Exception $e) {
            Log::error('Customer registration error', [
                'error' => $e->getMessage()
            ]);
            
            return back()->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Registration failed. Please try again.');
        }
    }
}
