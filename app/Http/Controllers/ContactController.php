<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|max:150',
            'phone'        => 'nullable|string|max:30',
            'project_type' => 'nullable|string|max:100',
            'message'      => 'required|string|min:10|max:2000',
        ]);

        ContactMessage::create($validated);

        // Optional: send email notification
        // Mail::to('amarjafri1472@gmail.com')->send(new ContactMail($validated));

        return redirect()->route('home', ['#contact'])->with('success', 'Message sent successfully!');
    }
}
