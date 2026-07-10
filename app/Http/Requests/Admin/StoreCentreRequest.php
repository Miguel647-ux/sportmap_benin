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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'horaires' => 'nullable|string|max:255',
            'statut' => 'sometimes|in:brouillon,publie',
            'id_quartier' => 'required|exists:quartiers,id_quartier',
            'disciplines' => 'required|array|min:1',
            'disciplines.*' => 'exists:disciplines,id_discipline',
            'categorie_ages' => 'nullable|array',
            'categorie_ages.*' => 'exists:categorie_ages,id_categorie_age',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du centre est obligatoire.',
            'id_quartier.required' => 'Le quartier est obligatoire.',
            'disciplines.required' => 'Au moins une discipline est requise.',
            'disciplines.min' => 'Au moins une discipline est requise.',
        ];
    }
}