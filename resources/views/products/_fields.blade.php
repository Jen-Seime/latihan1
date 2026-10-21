@if ($errors->any())
    <div role="alert" class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">
        <p class="font-semibold mb-1">Periksa isian yang ditandai di bawah, lalu pilih ulang gambar.</p>
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



{{-- Gambar Produk --}}
<div>
    <label for="image" class="block font-semibold mb-2">
        {{ isset($product) ? 'Gambar Produk (opsional)' : 'Gambar Produk' }}
    </label>

    @if (isset($product) && $product->image)
        <div class="mb-3">
            <p class="text-xs text-slate-500 mb-1">Gambar yang saat ini tersimpan:</p>
            <img src="{{ asset('storage/products/' . $product->image) }}"
                 alt="{{ $product->title }}"
                 class="w-48 h-36 object-contain border border-green-200 rounded-lg">
        </div>
    @endif

    <input type="file" name="image" id="image"
        {{-- accept="image/jpeg,image/png" aria-describedby="image-help image-error" --}}
        @required(! isset($product))
        class="block w-full border rounded-lg p-3 @error('image') border-red-500 @else border-green-200 @enderror">
    <p id="image-help" class="text-sm text-slate-500 mt-1">
        {{ isset($product) ? 'Kosongkan untuk mempertahankan gambar lama. ' : '' }}JPG/JPEG/PNG, maksimal 2 MB.
    </p>
    <p id="image-error" class="text-sm text-red-700 mt-1">@error('image') {{ $message }} @enderror</p>

    {{-- Pratinjau Gambar Pilihan --}}
    <div class="mt-3">
        <img id="image-preview" alt="Pratinjau gambar pilihan"
             class="w-48 h-36 object-contain border border-green-200 rounded-lg" hidden>
        <p id="preview-status" class="text-sm text-slate-600 mt-1" role="status"></p>
    </div>
</div>


{{-- Kategori Produk --}}
<div>
    <label for="category_id" class="block font-semibold mb-2">Kategori Produk</label>
    <select id="category_id" name="category_id" required aria-describedby="category_id-error"
        class="w-full border rounded-lg p-3 bg-white @error('category_id') border-red-500 @else border-green-200 @enderror">
        <option value="">-- Pilih Kategori --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <p id="category_id-error" class="text-sm text-red-700 mt-1">@error('category_id') {{ $message }} @enderror</p>
</div>


{{-- Nama Produk --}}
<div>
    <label for="title" class="block font-semibold mb-2">Nama Produk</label>
    <input type="text" name="title" id="title" value="{{ old('title', $product->title ?? '') }}"
        {{-- required minlength="5" maxlength="255" aria-describedby="title-error" --}}
        placeholder="Contoh: Keyboard Wireless"
        class="w-full border rounded-lg p-3 @error('title') border-red-500 @else border-green-200 @enderror">
    <p id="title-error" class="text-sm text-red-700 mt-1">@error('title') {{ $message }} @enderror</p>
</div>

{{-- Deskripsi --}}
<div>
    <label for="description" class="block font-semibold mb-2">Deskripsi</label>
    <textarea name="description" id="description" rows="5" required
        {{-- minlength="10" maxlength="10000" aria-describedby="description-error" --}}
        placeholder="Tuliskan deskripsi produk minimal 10 karakter."
        class="w-full border rounded-lg p-3 @error('description') border-red-500 @else border-green-200 @enderror">{{ old('description', $product->description ?? '') }}</textarea>
    <p id="description-error" class="text-sm text-red-700 mt-1">@error('description') {{ $message }} @enderror</p>
</div>

{{-- Harga & Stok --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label for="price" class="block font-semibold mb-2">Harga (Rupiah)</label>
        <input type="number" name="price" id="price" value="{{ old('price', $product->price ?? '') }}"
            {{-- min="0" max="999999999999" step="1" required aria-describedby="price-help price-error" --}}
            placeholder="150000"
            class="w-full border rounded-lg p-3 @error('price') border-red-500 @else border-green-200 @enderror">
        <p id="price-help" class="text-sm text-slate-500 mt-1">Angka bulat, tanpa Rp atau pemisah ribuan.</p>
        <p id="price-error" class="text-sm text-red-700 mt-1">@error('price') {{ $message }} @enderror</p>
    </div>
    <div>
        <label for="stock" class="block font-semibold mb-2">Stok</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? 0) }}"
            min="0" max="2147483647" step="1" required aria-describedby="stock-error"
            class="w-full border rounded-lg p-3 @error('stock') border-red-500 @else border-green-200 @enderror">
        <p id="stock-error" class="text-sm text-red-700 mt-1">@error('stock') {{ $message }} @enderror</p>
    </div>
</div>

<script>
(() => {
    const input = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const status = document.getElementById('preview-status');
    if (!input || !preview || !status) return;

    let objectUrl;
    input.addEventListener('change', () => {
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        preview.hidden = true;
        preview.removeAttribute('src');
        status.textContent = '';
        const file = input.files[0];
        if (!file) return;
        if (!['image/jpeg', 'image/png'].includes(file.type) || file.size > 2097152) {
            status.textContent = 'Pilih JPG/PNG dengan ukuran maksimal 2 MB.';
            input.value = '';
            return;
        }
        objectUrl = URL.createObjectURL(file);
        preview.src = objectUrl;
        preview.hidden = false;
        status.textContent = 'Pratinjau gambar pilihan. Klik Simpan/Update untuk mengunggah.';
    });
})();
</script>