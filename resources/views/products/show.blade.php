@extends('layouts.app')
@section('title', 'Detail Produk')
@section('content')
<article class="panel">
    <h2>{{ $product->title }}</h2>
    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->title }}" width="280">
    <p>Kategori: {{ $product->category?->name ?? 'Belum dikategorikan' }}</p>
    <p style="white-space:pre-wrap">{{ $product->description }}</p>
    <p>Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
    <p>Stok: {{ $product->stock }}</p>
    @if (auth()->user()->isAdmin())
        <a class="button" href="{{ route('products.edit', $product->id) }}">Edit</a>
    @endif
    <a href="{{ route('products.index') }}">Kembali</a>
</article>
@endsection