<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'type' => 'sometimes|in:logo,photo',
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Veuillez sélectionner une image.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'Format autorisé : jpeg, png, jpg, webp.',
            'photo.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}