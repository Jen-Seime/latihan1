<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | WBF Laravel Bagian 11</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f2f5ec; color: #142d22; font: 16px/1.6 Arial, sans-serif; }
        main { max-width: 520px; margin: 40px auto; padding: 0 16px; }
        header { background: #f9d64b; border-radius: 18px 18px 0 0; padding: 24px; }
        h1 { margin: 8px 0; font-size: 28px; }
        .card { background: white; padding: 24px; border-radius: 0 0 18px 18px; }
        label { display: block; font-weight: bold; margin-top: 14px; }
        input { width: 100%; padding: 11px; border: 1px solid #a9bca9; border-radius: 7px; font: inherit; }
        button { width: 100%; padding: 12px; background: #145a3b; color: white; border: 0; border-radius: 8px; font: inherit; cursor: pointer; margin-top: 20px; }
        a { color: #145a3b; }
        .error { background: #fff0e9; color: #842824; padding: 12px; }
        .success { background: #e5f2de; padding: 12px; }
        :focus-visible { outline: 3px solid #b66f00; outline-offset: 3px; }
    </style>
</head>
<body>
<main>
    <header>
        <strong>WBF Laravel — Hasil Praktikum Bagian 11</strong>
        <h1>@yield('title')</h1>
        <p>Autentikasi dan Hak Akses Pengguna</p>
    </header>
    <section class="card">
        @if (session('success')) <p class="success" role="status">{{ session('success') }}</p> @endif
        @if ($errors->any())
            <div class="error" role="alert"><ul>
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul></div>
        @endif
        @yield('content')
    </section>
</main>
</body>
</html>