<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Alat Tulis' => Category::firstOrCreate(['name' => 'Alat Tulis'])->id,
            'Elektronik' => Category::firstOrCreate(['name' => 'Elektronik'])->id,
            'Perlengkapan Kelas' => Category::firstOrCreate(['name' => 'Perlengkapan Kelas'])->id,
        ];

        $items = [
            [
                'title' => 'Pulpen Pilot',
                'category_id' => $categories['Alat Tulis'],
                'description' => 'Pulpen tinta hitam untuk menulis',
                'price' => 5000,
                'stock' => 100,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Buku Tulis Sidu',
                'category_id' => $categories['Alat Tulis'],
                'description' => 'Buku tulis 38 lembar untuk mencatat materi',
                'price' => 4000,
                'stock' => 150,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Pensil 2B Faber-Castell',
                'category_id' => $categories['Alat Tulis'],
                'description' => 'Pensil 2B untuk menulis dan menggambar',
                'price' => 3500,
                'stock' => 80,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Penghapus Joyko',
                'category_id' => $categories['Alat Tulis'],
                'description' => 'Penghapus pensil untuk mengoreksi tulisan',
                'price' => 2000,
                'stock' => 60,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Laptop Lenovo',
                'category_id' => $categories['Elektronik'],
                'description' => 'Laptop untuk kegiatan belajar dan presentasi',
                'price' => 6500000,
                'stock' => 10,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Proyektor Epson',
                'category_id' => $categories['Elektronik'],
                'description' => 'Proyektor untuk menampilkan materi pembelajaran',
                'price' => 4500000,
                'stock' => 5,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Speaker Aktif',
                'category_id' => $categories['Elektronik'],
                'description' => 'Speaker untuk mendukung kegiatan pembelajaran',
                'price' => 750000,
                'stock' => 8,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Papan Tulis Putih',
                'category_id' => $categories['Perlengkapan Kelas'],
                'description' => 'Papan tulis untuk menulis materi pelajaran',
                'price' => 350000,
                'stock' => 12,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Kursi Kuliah',
                'category_id' => $categories['Perlengkapan Kelas'],
                'description' => 'Kursi untuk mahasiswa saat mengikuti perkuliahan',
                'price' => 250000,
                'stock' => 40,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
            [
                'title' => 'Meja Belajar',
                'category_id' => $categories['Perlengkapan Kelas'],
                'description' => 'Meja untuk menunjang aktivitas belajar mahasiswa',
                'price' => 450000,
                'stock' => 25,
                'image' => 'CraBrAprTcD6Q3jNiLfE4jE006fOa1f1zRX7ZGu7.png',
            ],
        ];

        foreach ($items as $item) {
            Product::firstOrCreate(
                ['title' => $item['title']],
                $item
            );
        }
    }
}
