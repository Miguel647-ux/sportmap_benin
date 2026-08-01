<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'nullable|string|max:100',
            'email' => 'required|email|max:150',
            'message' => 'required|string|min:3|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contact = Contact::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'message' => $request->message,
            'lu' => false,
        ]);

        return response()->json([
            'message' => 'Votre message a été envoyé avec succès.',
            'contact' => $contact,
        ], 201);
    }
}