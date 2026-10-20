@extends('layouts.app')
@section('title', 'Edit Produk')
@section('content')
<section class="panel">
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('products._fields')
        <button type="submit">Simpan perubahan</button>
        <a href="{{ route('products.index') }}">Kembali</a>
    </form>
</section>
@endsection