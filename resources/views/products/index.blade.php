@extends('layouts.app')
@section('title', 'Produk Aktif')
@section('content')
@if ($errors->any())
        <div class="notice" role="alert">
            @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
            <a href="{{ route('products.index') }}">Buka daftar tanpa filter</a>
        </div>
    @endif

    <form class="filters" method="GET" action="{{ route('products.index') }}">
        <div>
            <label for="q">Cari produk</label>
            <input id="q" name="q" value="{{ $q }}" maxlength="100"
                placeholder="Nama atau deskripsi">
        </div>
        <div>
            <label for="category_id">Filter kategori</label>
            <select id="category_id" name="category_id">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected((string) $categoryId === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit">Terapkan</button>
        <a href="{{ route('products.index') }}">Reset</a>
        @if (auth()->user()->isAdmin())
            <a class="button" href="{{ route('products.create') }}">+ Produk</a>
        @endif
    </form>

    <p><strong>{{ $products->total() }} produk ditemukan.</strong>
        Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}.
        @if ($q !== '') Kata kunci: “{{ $q }}”. @endif
    </p>

    <div class="table-wrap">
        <table>
            <thead><tr><th>No.</th><th>Gambar</th><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td><img src="{{ asset('storage/products/' . $product->image) }}"
                            alt="{{ $product->title }}" width="72" loading="lazy"></td>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->category?->name ?? 'Belum dikategorikan' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td><div class="actions">
                            <a href="{{ route('products.show', $product->id) }}">Detail</a>
                            @if (auth()->user()->isAdmin())
                            <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                            <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                onsubmit="return confirm('Pindahkan produk ke tempat sampah? Gambar tetap disimpan.')">
                                @csrf
                                @method('DELETE')
                                <button class="danger" type="submit">Ke tempat sampah</button>
                            </form>
                            @endif
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="7">Tidak ada produk yang sesuai. Ubah filter atau klik Reset.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <nav aria-label="Navigasi halaman produk">
        @if ($products->previousPageUrl())
            <a class="button" href="{{ $products->previousPageUrl() }}">← Sebelumnya</a>
        @endif
        <span>Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
        @if ($products->hasMorePages())
            <a class="button" href="{{ $products->nextPageUrl() }}">Berikutnya →</a>
        @endif
    </nav>
@endsection