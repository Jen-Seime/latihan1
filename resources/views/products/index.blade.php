<x-layout>
    <main class="max-w-6xl mx-auto px-4 py-8">
        {{-- Banner Bagian 10 & Identitas --}}
        {{-- <header class="bg-yellow-300 border-2 border-green-900 rounded-2xl p-6 mb-6 shadow-sm">
            <span class="badge inline-block bg-green-900 text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                WBF Laravel — Hasil Praktikum Bagian 10
            </span>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mt-1">
                Relasi Kategori, Pencarian, Filter, dan Pagination Produk
            </h1>
            <p class="text-slate-800 font-medium mt-2 text-sm md:text-base">
                Nama: Nyoman adi putra &nbsp;|&nbsp; NIM: 2404411099 &nbsp;|&nbsp; Kelas: 3gab 4web
            </p>
        </header> --}}

        <title>Bagian 11 | WBF Laravel</title>

        @include('products._authbar')
        

        {{-- Flash Messages / Alerts --}}
        @foreach (['success', 'warning', 'error'] as $key)
            @if (session($key))
                <div class="mb-4 bg-white border-l-4 border-green-800 p-4 rounded-r-lg shadow-sm text-slate-800 font-medium" role="status">
                    {{ session($key) }}
                </div>
            @endif
        @endforeach

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-600 p-4 rounded-r-lg shadow-sm text-red-800" role="alert">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
                <a href="{{ route('products.index') }}" class="inline-block text-xs font-semibold text-red-700 underline mt-2 hover:text-red-900">
                    Buka daftar tanpa filter
                </a>
            </div>
        @endif

        {{-- Filter & Search Form --}}
        <section class="bg-white rounded-2xl border border-green-200 p-6 mb-6 shadow-sm">
            <form class="flex flex-wrap items-end gap-4" method="GET" action="{{ route('products.index') }}">
                <div class="flex-1 min-w-[220px]">
                    <label for="q" class="block text-sm font-semibold text-slate-700 mb-1">Cari produk</label>
                    <input id="q" name="q" value="{{ $q }}" maxlength="100"
                        placeholder="Nama atau deskripsi..."
                        class="w-full border border-green-200 rounded-lg px-3 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-green-700 text-sm">
                </div>

                <div class="w-full sm:w-auto min-w-[200px]">
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">Filter kategori</label>
                    <select id="category_id" name="category_id"
                        class="w-full border border-green-200 rounded-lg px-3 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-green-700 text-sm">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected((string) $categoryId === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="bg-green-800 hover:bg-green-900 text-white font-medium px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                        Terapkan
                    </button>
                    <a href="{{ route('products.index') }}"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-4 py-2 rounded-lg text-sm transition">
                        Reset
                    </a>
                </div>

                {{-- <div class="w-full sm:w-auto sm:ml-auto">
                    <a href="{{ route('products.create') }}"
                        class="inline-block bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-4 py-2 rounded-lg text-sm transition shadow-sm">
                        + Produk Baru
                    </a>
                </div> --}}

                @if (auth()->user()->isAdmin())
                    <a class="button" href="{{ route('products.create') }}">+ Produk</a>
                @endif
            </form>
        </section>

        {{-- Info Pencarian / Total Data --}}
        <div class="flex flex-wrap items-center justify-between text-sm text-slate-600 mb-3 px-1">
            <p>
                <strong class="text-slate-800">{{ $products->total() }} produk ditemukan.</strong>
                Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}.
                @if ($q !== '')
                    Kata kunci: <span class="font-semibold text-green-900">“{{ $q }}”</span>.
                @endif
                @if ($categoryId && ($activeCategory = $categories->firstWhere('id', $categoryId)))
                    Kategori: <span class="font-semibold text-green-900">“{{ $activeCategory->name }}”</span>.
                @endif
            </p>
        </div>

        {{-- Tabel Produk --}}
        <div class="bg-white rounded-2xl border border-green-200 overflow-hidden shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-green-900 text-white text-xs uppercase tracking-wider">
                            <th class="px-4 py-3.5 font-semibold text-center w-12">No.</th>
                            <th class="px-4 py-3.5 font-semibold w-24">Gambar</th>
                            <th class="px-4 py-3.5 font-semibold">Produk</th>
                            <th class="px-4 py-3.5 font-semibold">Kategori</th>
                            <th class="px-4 py-3.5 font-semibold">Harga</th>
                            <th class="px-4 py-3.5 font-semibold">Stok</th>
                            <th class="px-4 py-3.5 font-semibold text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800 text-sm">
                        @forelse ($products as $product)
                            <tr class="hover:bg-green-50/50 transition">
                                <td class="px-4 py-3 text-center text-slate-500 font-medium">
                                    {{ $products->firstItem() + $loop->index }}
                                </td>
                                <td class="px-4 py-3">
                                    <img src="{{ asset('storage/products/' . $product->image) }}"
                                        alt="{{ $product->title }}"
                                        class="w-14 h-14 object-cover rounded-lg border border-slate-200 bg-slate-50"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2250%22 height=%2250%22><rect width=%2250%22 height=%2250%22 fill=%22%23e2e8f0%22/><text x=%2250%%22 y=%2250%%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%2394a3b8%22 font-size=%2210%22>No Img</text></svg>';">
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-900">
                                    {{ $product->title }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($product->category)
                                        <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            {{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="inline-block bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-full">
                                            Belum dikategorikan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700 whitespace-nowrap">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block font-semibold {{ $product->stock > 0 ? 'text-slate-800' : 'text-red-600' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('products.show', $product->id) }}"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1 rounded-md text-xs font-semibold transition">
                                            Detail
                                        </a>
                                        {{-- <a href="{{ route('products.edit', $product->id) }}"
                                            class="bg-amber-100 hover:bg-amber-200 text-amber-800 px-2.5 py-1 rounded-md text-xs font-semibold transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                            onsubmit="return confirm('Hapus produk dan gambar terkait?')"
                                            class="inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-2.5 py-1 rounded-md text-xs font-semibold transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form> --}}

                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                                            <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                                onsubmit="return confirm('Hapus produk dan gambar terkait?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="danger" type="submit">Hapus</button>
                                            </form>
                                            
                                        @endif

                                         <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                            onsubmit="return confirm('Hapus produk dan gambar terkait?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="danger" type="submit">Hapus</button>
                                        </form>

                                        {{-- <form method="POST" action="{{ route('products.destroy', 123) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Uji DELETE sebagai pengguna — harus ditolak</button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                    Tidak ada produk yang sesuai. Ubah kata kunci pencarian, pilih kategori lain, atau klik
                                    <a href="{{ route('products.index') }}" class="text-green-800 font-semibold underline">Reset</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-wrap items-center justify-between gap-4 py-2">
            <div>
                @if ($products->previousPageUrl())
                    <a href="{{ $products->previousPageUrl() }}"
                        class="inline-block bg-green-800 hover:bg-green-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
                        ← Sebelumnya
                    </a>
                @endif
            </div>

            <span class="text-sm text-slate-600 font-medium">
                Halaman <span class="font-bold text-slate-800">{{ $products->currentPage() }}</span> dari <span class="font-bold text-slate-800">{{ $products->lastPage() }}</span>
            </span>

            <div>
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                        class="inline-block bg-green-800 hover:bg-green-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
                        Berikutnya →
                    </a>
                @endif
            </div>
        </div>
    </main>

    @if (session('success'))
        <script>
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: {{ Illuminate\Support\Js::from(session('success')) }},
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        </script>
    @endif
</x-layout>