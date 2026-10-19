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


            <form action="{{ route('products.update', $product->id) }}" method="POST"
                  enctype="multipart/form-data" class="space-y-5" novalidate>
                @csrf
                @method('PUT')

                @include('products._fields')

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