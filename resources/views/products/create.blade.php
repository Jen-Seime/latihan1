<x-layout>
     <main class="max-w-4xl mx-auto px-4 py-10">
        

        <title>Bagian 11 | WBF Laravel</title>

        @include('products._authbar')

        <section class="bg-white rounded-2xl border border-green-200 p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h2 class="text-xl font-semibold">Form Produk Baru</h2>
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 rounded-lg bg-slate-800 text-white">Kembali</a>
            </div>

           
            <form action="{{ route('products.store') }}" method="POST"
                  enctype="multipart/form-data" class="space-y-5" novalidate>
                @csrf

                @include('products._fields')

                <button type="submit" class="bg-green-800 hover:bg-green-900 text-white font-semibold px-6 py-3 rounded-lg">
                    Simpan Produk
                </button>
            </form>
        </section>
    </main>
</x-layout>