<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Praktikum lokal. Hak akses akan dibahas pada Bagian 11.
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['bail', 'required', 'string', 'min:5', 'max:255'],
            'description' => ['bail', 'required', 'string', 'min:10', 'max:10000'],
            'price' => ['bail', 'required', 'integer', 'min:0', 'max:999999999999'],
            'stock' => ['bail', 'required', 'integer', 'min:0', 'max:2147483647'],
            'image' => ['bail', 'required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'title.min' => 'Nama produk minimal 5 karakter.',
            'title.max' => 'Nama produk maksimal 255 karakter.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'description.max' => 'Deskripsi maksimal 10.000 karakter.',
            'price.min' => 'Harga tidak boleh negatif.',
            'price.max' => 'Harga melebihi batas latihan.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'stock.max' => 'Stok melebihi batas kolom database.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gunakan gambar JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 2048 KB (2 MB).',
            'image.uploaded' => 'Upload gagal. Periksa ukuran file dan konfigurasi PHP.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Nama produk', 'description' => 'Deskripsi',
            'price' => 'Harga', 'stock' => 'Stok', 'image' => 'Gambar',
        ];
    }
}