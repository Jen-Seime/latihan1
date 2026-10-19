<x-layout>
     <main class="max-w-6xl mx-auto px-4 py-10">
        <x-header judul="WBF Laravel • Bagian 04"></x-header>

        @if (session('success'))
            <div role="status" class="bg-green-100 text-green-900 border border-green-300 rounded-lg p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div role="alert" class="bg-yellow-100 text-yellow-900 border border-yellow-400 rounded-lg p-4 mb-4">
                {{ session('warning') }}
            </div>
        @endif
        @if (session('error'))
            <div role="alert" class="bg-red-100 text-red-900 border border-red-300 rounded-lg p-4 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <section class="bg-white rounded-2xl border border-green-200 p-6">
            <div class="flex flex-wrap items-center gap-3 mb-5">
               <div class="flex flex-wrap items-center gap-3 mb-5">
                    <a href="{{ route('products.create') }}"
                    class="bg-green-800 hover:bg-green-900 text-white px-4 py-2 rounded-lg">
                        Tambah Produk
                    </a>
                    <p class="text-sm text-slate-500">Tambahkan produk beserta gambar melalui form.</p>
                </div>
                <p class="text-sm text-slate-500">Fitur perubahan data belum diaktifkan.</p>
            </div>

            @if (session('success'))
                <div role="status" class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 p-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('warning'))
                <div role="status" class="mb-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 p-4">
                    {{ session('warning') }}
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 p-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-green-100">
                    <thead class="bg-green-800 text-white">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Gambar</th>
                            <th scope="col" class="px-4 py-3 text-left">Nama Produk</th>
                            <th scope="col" class="px-4 py-3 text-left">Harga</th>
                            <th scope="col" class="px-4 py-3 text-left">Stok</th>
                            <th scope="col" class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b border-green-100">
                                <td class="px-4 py-3">
                                    <img
                                        src="{{ asset('storage/products/' . $product->image) }}"
                                        alt="{{ $product->title }}"
                                        width="150" height="100" loading="lazy"
                                        class="w-[150px] h-[100px] object-cover rounded-lg">
                                </td>
                                <td class="px-4 py-3 font-semibold">
                                    {{ $product->title }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ 'Rp ' . number_format($product->price, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">{{ $product->stock }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.show', $product->id) }}"
                                        class="bg-green-800 hover:bg-green-900 text-white px-3 py-2 rounded">
                                            Detail
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}"
                                         class="bg-yellow-300 hover:bg-yellow-400 text-green-900 px-3 py-2 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Hapus produk ini beserta gambarnya? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-700 hover:bg-red-800 text-white px-3 py-2 rounded">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                   
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5">
                                       <p class="mt-1 text-slate-600">Klik Tambah Produk untuk membuat data pertama.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">{{ $products->links() }}</div>
        </section>
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