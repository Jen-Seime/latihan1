@extends('auth.layout')
@section('title', 'Registrasi pengguna')
@section('content')
<p>Akun baru memiliki akses pengguna: melihat dan menelusuri produk.</p>
<form method="POST" action="{{ route('register.store') }}">
    @csrf
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name') }}" maxlength="100"
        autocomplete="name" required>
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}"
        maxlength="255" autocomplete="username" required>
    <label for="password">Password (12–64 karakter)</label>
    <input id="password" name="password" type="password" minlength="12" maxlength="64"
        autocomplete="new-password" required>
    <label for="password_confirmation">Konfirmasi password</label>
    <input id="password_confirmation" name="password_confirmation" type="password"
        autocomplete="new-password" required>
    <button type="submit">Daftar</button>
</form>
<p>Sudah terdaftar? <a href="{{ route('login') }}">Login</a></p>
@endsection