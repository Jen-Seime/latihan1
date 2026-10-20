@extends('auth.layout')
@section('title', 'Login')
@section('content')
<form method="POST" action="{{ route('login.store') }}">
    @csrf
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}"
        maxlength="255" autocomplete="username" required autofocus>
    <label for="password">Password</label>
    <input id="password" name="password" type="password"
        autocomplete="current-password" required>
    <button type="submit">Masuk</button>
</form>
<p>Belum memiliki akun? <a href="{{ route('register') }}">Daftar pengguna</a></p>
@endsection