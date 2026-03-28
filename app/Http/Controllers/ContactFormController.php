<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMessage;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactFormController extends Controller
{
    public function send(Request $request)
    {
        // Debug
//        Log::info('Incoming request', $request->all());
        $company = Company::orderByDesc('id')->first();
        
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email',
            'subject'     => 'required|string',
            'comments' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            Log::info('Validator Failed', $validator->errors()->all());
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        Mail::to($company->email)->send(new ContactFormMessage($request->only('name', 'email', 'subject', 'comments')));
        
        return response()->json('Message sent successfully!');
    }
}
