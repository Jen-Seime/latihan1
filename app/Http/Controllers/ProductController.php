<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PHPUnit\Event\Code\Throwable;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
        'q' => ['nullable', 'string', 'max:100'],
        'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $q = trim($filters['q'] ?? '');
        $categoryId = $filters['category_id'] ?? null;
        $categories = Category::orderBy('name')->get();
        $query = Product::with('category');

        if ($q !== '') {
            // Kelompokkan OR agar tetap tunduk pada filter kategori.
            $query->where(function ($subquery) use ($q) {
                $subquery->where('title', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderByDesc('id')->paginate(5)
            ->appends(['q' => $q, 'category_id' => $categoryId]);

        return view('products.index', compact('products', 'categories', 'q', 'categoryId'));
            $products = Product::latest()->paginate(10);

        
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
        
    }

    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
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

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        

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
                'category_id' => $validated['category_id'],
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
