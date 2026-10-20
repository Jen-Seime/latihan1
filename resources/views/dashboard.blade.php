@extends('layouts.app')
@section('title', 'Dashboard Produk')
@section('content')
<div class="stats">
    <x-stat-card label="Produk aktif" :value="$activeProducts" detail="Jumlah jenis produk, bukan jumlah unit." />
    <x-stat-card label="Total stok aktif" :value="$totalStock" detail="Jumlah seluruh unit pada produk aktif." />
    <x-stat-card label="Kategori" :value="$categoryCount" detail="Semua kategori, termasuk yang belum terisi." />
    @if (auth()->user()->isAdmin())
        <x-stat-card label="Di tempat sampah" :value="$trashCount" detail="Produk yang masih dapat dipulihkan." />
    @endif
</div>
<section class="panel">
    <h2>Lima produk aktif terbaru</h2>
    <div class="table-wrap"><table>
        <thead><tr><th>Produk</th><th>Kategori</th><th>Stok</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse ($latestProducts as $product)
            <tr>
                <td>{{ $product->title }}</td>
                <td>{{ $product->category?->name ?? 'Belum dikategorikan' }}</td>
                <td>{{ $product->stock }}</td>
                <td><a href="{{ route('products.show', $product->id) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="4">Belum ada produk aktif.</td></tr>
        @endforelse
        </tbody>
    </table></div>
</section>
@endsection
