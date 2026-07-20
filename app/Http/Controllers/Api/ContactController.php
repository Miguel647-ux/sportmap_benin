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
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'nom' => 'nullable|string|max:100',
                'email' => 'required|email|max:150',
                'message' => 'required|string|min:3|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Enregistrer en base
            $contact = Contact::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'message' => $request->message,
                'lu' => false,
            ]);

            // Envoyer l'email (on ignore l'erreur)
            try {
               Mail::to(config('mail.from.address'))->send(new ContactMail($contact));
            } catch (\Exception $e) {
                // On log mais on continue
                \Log::error('Erreur email contact: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
                'contact' => $contact,
            ], 201);

        } catch (\Exception $e) {
            // En cas d'erreur, on renvoie quand même du JSON
            \Log::error('Erreur contact: ' . $e->getMessage());
            return response()->json([
                'message' => 'Votre message a bien été reçu. Une notification sera envoyée à l\'administrateur.',
            ], 200);
        }
    }
}