<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | WBF Laravel Bagian 12</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f2f5ec; color: #142d22; font: 16px/1.6 Arial, sans-serif; }
        main { max-width: 1120px; margin: auto; padding: 24px; }
        header { background: #f9d64b; border: 2px solid #145a3b; padding: 24px; border-radius: 18px; }
        h1 { margin: 8px 0; font-size: 30px; }
        h2 { font-size: 22px; }
        a { color: #145a3b; }
        nav, .filters, .actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        nav, .filters { margin: 20px 0; }
        form { margin: 0; }
        label { display: block; font-weight: bold; }
        input, select, textarea { font: inherit; padding: 10px; border: 1px solid #abc1ab; border-radius: 7px; max-width: 100%; }
        .form-control { width: 100%; }
        .mb-3 { margin: 16px 0; }
        button, .button, .btn { display: inline-block; background: #145a3b; color: white; border: 0; padding: 10px 14px; border-radius: 7px; font: inherit; cursor: pointer; text-decoration: none; }
        .danger { background: #a32d28; }
        .panel, .stat, .notice { background: white; padding: 20px; border: 1px solid #d5e2d0; border-radius: 12px; margin: 16px 0; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap: 16px; }
        .stat .value { font-size: 36px; font-weight: bold; color: #145a3b; margin: 8px 0; }
        .stat p { margin: 4px 0; }
        .table-wrap { overflow-x: auto; background: white; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #dce5db; }
        th { background: #145a3b; color: white; }
        .text-danger, .alert-danger { color: #a32d28; }
        .alert { padding: 12px; background: #fff0d6; margin: 12px 0; }
        .notice[data-type="error"] { border-left: 5px solid #a32d28; }
        .notice[data-type="warning"] { border-left: 5px solid #b17b00; }
        img { max-width: 100%; height: auto; }
        :focus-visible { outline: 3px solid #b66f00; outline-offset: 3px; }
        @media(max-width:600px) { main { padding: 14px; } h1 { font-size: 25px; } }
    </style>
    @stack('styles')
</head>
<body>
<main>
    <header>
        <strong>WBF Laravel — Hasil Praktikum Bagian 12</strong>
        <h1>@yield('title', 'Dashboard')</h1>
        <p>Dashboard, Layout Blade, dan Pemulihan Data</p>
        <p>Nyoman adi putra | 2404411099 | 3gab 4web</p>
    </header>
    <nav aria-label="Navigasi aplikasi">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('products.index') }}">Produk aktif</a>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}">Tambah produk</a>
            <a href="{{ route('products.trash') }}">Tempat sampah</a>
        @endif
        <span>{{ auth()->user()->name }} · {{ auth()->user()->isAdmin() ? 'Admin' : 'Pengguna' }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
    @include('partials.flash')
    @yield('content')
</main>
@stack('scripts')
</body>
</html>
