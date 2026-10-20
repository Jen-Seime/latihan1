@extends('layouts.app')
@section('title', 'Tempat Sampah Produk')
@section('content')
<p>Produk di sini tidak tampil pada daftar aktif. Gambar tetap disimpan sampai produk dihapus permanen.</p>
<p><strong>{{ $products->total() }} produk</strong> di tempat sampah.</p>
<div class="table-wrap"><table>
    <thead><tr><th>Produk</th><th>Kategori</th><th>Dihapus pada</th><th>Aksi admin</th></tr></thead>
    <tbody>
    @forelse ($products as $product)
        <tr>
            <td>{{ $product->title }}</td>
            <td>{{ $product->category?->name ?? 'Belum dikategorikan' }}</td>
            <td>{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
            <td><div class="actions">
                <form method="POST" action="{{ route('products.restore', $product->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Pulihkan</button>
                </form>
                <form method="POST" action="{{ route('products.force-destroy', $product->id) }}"
                    onsubmit="return confirm('Hapus permanen produk dan gambarnya? Tindakan ini tidak dapat dibatalkan melalui aplikasi.')">
                    @csrf
                    @method('DELETE')
                    <button class="danger" type="submit">Hapus permanen</button>
                </form>
            </div></td>
        </tr>
    @empty
        <tr><td colspan="4">Tempat sampah kosong.</td></tr>
    @endforelse
    </tbody>
</table></div>
<nav aria-label="Halaman tempat sampah">
    @if ($products->previousPageUrl())
        <a class="button" href="{{ $products->previousPageUrl() }}">← Sebelumnya</a>
    @endif
    <span>Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
    @if ($products->hasMorePages())
        <a class="button" href="{{ $products->nextPageUrl() }}">Berikutnya →</a>
    @endif
</nav>
@endsection
