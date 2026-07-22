<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();
        return response()->json($contacts);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    public function markAsRead(Contact $contact)
    {
        $contact->marquerCommeLu();
        return response()->json(['message' => 'Message marqué comme lu']);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(['message' => 'Message supprimé avec succès']);
    }
}