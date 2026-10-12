<x-layout>
    <main class="max-w-4xl mx-auto px-4 py-10">
        <header class="bg-yellow-300 border-2 border-green-900 rounded-2xl p-6 mb-6">
            <p class="text-sm font-semibold text-green-900">WBF Laravel • Bagian 07</p>
            <h1 class="text-3xl font-bold mt-2">Edit Produk</h1>
            <p class="mt-2">MK Website Berbasis Framework — Laravel 13</p>
        </header>

        <section class="bg-white rounded-2xl border border-green-200 p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h2 class="text-xl font-semibold">Edit Produk #{{ $product->id }}</h2>
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 rounded-lg bg-slate-800 text-white">Kembali</a>
            </div>

            @if ($errors->any())
                <div role="alert" class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">
                    Periksa isian yang ditandai. Pilih ulang gambar jika ingin menggantinya.
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST"
                  enctype="multipart/form-data" class="space-y-5" novalidate>
                @csrf
                @method('PUT')

                <div>
                    <label for="image" class="block font-semibold mb-2">Gambar Produk (opsional)</label>
                    <img src="{{ asset('storage/products/' . $product->image) }}"
                         alt="{{ $product->title }}"
                         class="w-48 h-36 object-contain border border-green-200 rounded-lg mb-3">
                    <input type="file" name="image" id="image"
                        accept="image/jpeg,image/png" aria-describedby="image-help image-error"
                        class="block w-full border rounded-lg p-3 @error('image') border-red-500 @else border-green-200 @enderror">
                    <p id="image-help" class="text-sm text-slate-500 mt-1">Kosongkan untuk mempertahankan gambar lama. JPG/JPEG/PNG maksimal 2 MB.</p>
                    <p id="image-error" class="text-sm text-red-700 mt-1">@error('image') {{ $message }} @enderror</p>
                </div>

                <div>
                    <label for="title" class="block font-semibold mb-2">Nama Produk</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $product->title) }}"
                        required minlength="5" maxlength="255" aria-describedby="title-error"
                        placeholder="Contoh: Keyboard Wireless"
                        class="w-full border rounded-lg p-3 @error('title') border-red-500 @else border-green-200 @enderror">
                    <p id="title-error" class="text-sm text-red-700 mt-1">@error('title') {{ $message }} @enderror</p>
                </div>

                <div>
                    <label for="description" class="block font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="5" required
                        minlength="10" maxlength="10000" aria-describedby="description-error"
                        placeholder="Tuliskan deskripsi produk minimal 10 karakter."
                        class="w-full border rounded-lg p-3 @error('description') border-red-500 @else border-green-200 @enderror">{{ old('description', $product->description) }}</textarea>
                    <p id="description-error" class="text-sm text-red-700 mt-1">@error('description') {{ $message }} @enderror</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="price" class="block font-semibold mb-2">Harga (Rupiah)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                            min="0" max="999999999999" step="1" required aria-describedby="price-help price-error"
                            placeholder="150000"
                            class="w-full border rounded-lg p-3 @error('price') border-red-500 @else border-green-200 @enderror">
                        <p id="price-help" class="text-sm text-slate-500 mt-1">Angka bulat, tanpa Rp atau pemisah ribuan.</p>
                        <p id="price-error" class="text-sm text-red-700 mt-1">@error('price') {{ $message }} @enderror</p>
                    </div>
                    <div>
                        <label for="stock" class="block font-semibold mb-2">Stok</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                            min="0" max="2147483647" step="1" required aria-describedby="stock-error"
                            class="w-full border rounded-lg p-3 @error('stock') border-red-500 @else border-green-200 @enderror">
                        <p id="stock-error" class="text-sm text-red-700 mt-1">@error('stock') {{ $message }} @enderror</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="bg-green-800 hover:bg-green-900 text-white font-semibold px-6 py-3 rounded-lg">
                        Update Produk
                    </button>
                    <button type="reset" class="bg-yellow-300 text-green-900 font-semibold px-6 py-3 rounded-lg">
                        Reset Isian
                    </button>
                </div>
            </form>
        </section>
    </main>
</x-layout>