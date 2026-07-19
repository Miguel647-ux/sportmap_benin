<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCentreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
            'adresse' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:100',
            'quartier' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'horaires' => 'nullable|string|max:255',
            'statut' => 'sometimes|in:brouillon,publie',
            'disciplines' => 'required|array|min:1',
            'disciplines.*' => 'exists:disciplines,id_discipline',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du centre est obligatoire.',
            'disciplines.required' => 'Au moins une discipline est requise.',
            'disciplines.min' => 'Au moins une discipline est requise.',
        ];
    }
}