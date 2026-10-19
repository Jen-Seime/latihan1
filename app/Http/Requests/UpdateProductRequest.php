<?php

namespace App\Http\Requests;

class UpdateProductRequest extends StoreProductRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        // Saat edit, gambar lama boleh tetap digunakan.
        $rules['image'] = ['bail', 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'];

        return $rules;
    }
}