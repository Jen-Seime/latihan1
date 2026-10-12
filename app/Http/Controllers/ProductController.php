<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PHPUnit\Event\Code\Throwable;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:10000'],
            'price' => ['required', 'integer', 'min:0', 'max:999999999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ];

        $oldImage = $product->image;
        $newPath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->hashName();
            $newPath = $image->storeAs('products', $filename, 'public');

            if ($newPath === false) {
                return back()->withInput()->withErrors([
                    'image' => 'Gambar baru gagal disimpan. Gambar lama tetap digunakan.',
                ]);
            }

            $data['image'] = $filename;
        }

        try {
            $product->update($data);
        } catch (Throwable $exception) {
            // Upayakan pembersihan gambar baru jika update database gagal.
            if ($newPath !== null) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        // Database sudah diperbarui; baru upayakan hapus gambar lama.
        if ($newPath !== null && $oldImage) {
            try {
                $deleted = Storage::disk('public')->delete('products/' . $oldImage);

                if (! $deleted) {
                    report(new \RuntimeException('Gambar lama produk gagal dihapus.'));
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return redirect()->route('products.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }


    public function show(string $id): View
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:10000'],
            'price' => ['required', 'integer', 'min:0', 'max:999999999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ], [
            'required' => ':attribute wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gunakan gambar JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 2048 KB (2 MB).',
            'title.min' => 'Nama produk minimal 5 karakter.',
            'title.max' => 'Nama produk maksimal 255 karakter.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'description.max' => 'Deskripsi maksimal 10.000 karakter.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'price.min' => 'Harga tidak boleh negatif.',
            'price.max' => 'Harga melebihi batas latihan.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'stock.max' => 'Stok melebihi batas kolom database.',
        ], [
            'image' => 'Gambar',
            'title' => 'Nama produk',
            'description' => 'Deskripsi',
            'price' => 'Harga',
            'stock' => 'Stok',
        ]);

        $image = $request->file('image');
        $filename = $image->hashName();
        $path = $image->storeAs('products', $filename, 'public');

        if ($path === false) {
            return back()->withInput()
                ->withErrors(['image' => 'Gambar gagal disimpan. Silakan pilih ulang file.']);
        }

        try {
            Product::create([
                'image' => $filename,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
            ]);
        } catch (Throwable $exception) {
            // Upayakan pembersihan file jika insert database gagal.
            Storage::disk('public')->delete($path);
            throw $exception;
        }

        return redirect()->route('products.index')
            ->with('success', 'Data produk berhasil ditambahkan.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $image = $product->image;

        // Hapus record lebih dahulu. Jika gagal, gambar belum disentuh.
        if (! $product->delete()) {
            return redirect()->route('products.index')
                ->with('error', 'Data produk gagal dihapus. Silakan coba lagi.');
        }

        // Database dan filesystem tidak berada dalam satu transaksi.
        // Jika pembersihan gagal, catat dan tampilkan peringatan.
        if ($image) {
            try {
                if (! Storage::disk('public')->delete('products/' . $image)) {
                    throw new \RuntimeException('File gambar produk gagal dihapus.');
                }
            } catch (Throwable $exception) {
                report($exception);

                return redirect()->route('products.index')->with(
                    'warning',
                    'Data produk sudah dihapus, tetapi gambar belum berhasil dibersihkan. Hubungi pengelola.'
                );
            }
        }

        return redirect()->route('products.index')
            ->with('success', 'Data produk dan gambar terkait berhasil dihapus.');
    }

}
