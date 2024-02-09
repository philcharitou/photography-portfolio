<?php

namespace App\Http\Controllers;

use App;
use App\Mail\ContactForm;
use App\Mail\GenericEmail;
use App\Models\BugReport;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    public function contactForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:256',
            'last_name' => 'required|string|max:256',
            'email' => 'required|email',
            'type' => 'required|string|max:256',
            'date' => 'nullable|string|max:256',
            'location' => 'nullable|string|max:256',
            'message' => 'required|string|max:1024',
        ]);

        if ($validator->passes()) {
            Mail::to('contact@philcharitou.com')->send(new ContactForm($validator->getData()));

            return true;
        } else {
            return false;
        }
    }
}
