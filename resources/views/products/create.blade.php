@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
<section class="panel">
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('products._fields')
        <button type="submit">Simpan produk</button>
        <a href="{{ route('products.index') }}">Kembali</a>
    </form>
</section>
@endsection