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
use Throwable;

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
                $subquery->where('title', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%');
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
                $deleted = Storage::disk('public')->delete('products/'.$oldImage);

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

        if (! $product->delete()) {
            return redirect()->route('products.index')
                ->with('error', 'Produk gagal dipindahkan ke tempat sampah.');
        }

        // Soft delete: gambar tetap disimpan agar dapat dipulihkan.
        return redirect()->route('products.index')
            ->with('success', 'Produk dipindahkan ke tempat sampah. Gambar tetap disimpan.');
    }

    public function trash(): View
    {
        $products = Product::onlyTrashed()->with('category')
            ->orderByDesc('deleted_at')->orderByDesc('id')->paginate(5);

        return view('products.trash', compact('products'));
    }

    public function restore(string $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if (! $product->restore()) {
            return redirect()->route('products.trash')
                ->with('error', 'Produk gagal dipulihkan.');
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dipulihkan beserta referensi gambarnya.');
    }

    public function forceDestroy(string $id): RedirectResponse
    {
        // Hanya produk di tempat sampah yang boleh dihapus permanen.
        $product = Product::onlyTrashed()->findOrFail($id);
        $image = $product->image;

        if (! $product->forceDelete()) {
            return redirect()->route('products.trash')
                ->with('error', 'Penghapusan permanen gagal.');
        }

        // Record sudah terhapus. Upayakan pembersihan file setelahnya.
        if ($image) {
            try {
                if (! Storage::disk('public')->delete('products/'.$image)) {
                    throw new \RuntimeException('Gagal menghapus gambar produk permanen.');
                }
            } catch (Throwable $exception) {
                report($exception);

                return redirect()->route('products.trash')->with(
                    'warning',
                    'Record sudah dihapus permanen, tetapi gambar gagal dibersihkan. Periksa log.'
                );
            }
        }

        return redirect()->route('products.trash')
            ->with('success', 'Produk dan gambar terkait telah dihapus permanen.');
    }
}
