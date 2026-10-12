<x-layout>
     <section class="bg-white rounded-2xl border border-green-200 p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <p class="text-sm font-semibold text-green-800">
                    ID Produk: {{ $product->id }}
                </p>
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2 rounded-lg bg-green-800 hover:bg-green-900 text-white">
                    Kembali ke Daftar
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4">
                    <div class="border border-green-100 rounded-2xl overflow-hidden bg-amber-50 p-3">
                        <img src="{{ asset('storage/products/' . $product->image) }}"
                             alt="{{ $product->title }}"
                             class="w-full max-h-96 object-contain rounded-lg">
                    </div>
                </div>

                <div class="md:col-span-8 min-w-0">
                    <div class="border border-green-100 rounded-2xl p-5">
                        <h2 class="text-2xl font-bold break-words">{{ $product->title }}</h2>
                        <p class="text-xl font-semibold text-green-800 mt-3">
                            {{ 'Rp ' . number_format($product->price, 2, ',', '.') }}
                        </p>

                        <hr class="my-5 border-green-100">

                        <h3 class="font-semibold mb-2">Deskripsi Produk</h3>
                        <div class="whitespace-pre-line break-words text-slate-700">{{ $product->description }}</div>

                        <hr class="my-5 border-green-100">

                        <p>Stok: <strong>{{ $product->stock }}</strong> unit</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layout>