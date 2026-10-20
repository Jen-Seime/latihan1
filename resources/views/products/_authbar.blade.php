@auth
<section style="background:#e5f2de;border:1px solid #a8c3a3;border-radius:12px;padding:16px;margin:16px 0">
    <strong>WBF Laravel — Hasil Praktikum Bagian 11</strong>
    <p>Autentikasi dan Hak Akses Pengguna</p>
    <p>Identitas praktikum: NAMA MAHASISWA | NIM MAHASISWA | KELAS</p>
    <p>Masuk sebagai: <strong>{{ auth()->user()->name }}</strong>
        · {{ auth()->user()->email }}
        · Peran: <strong>{{ auth()->user()->isAdmin() ? 'Admin' : 'Pengguna' }}</strong>
    </p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background:#145a3b;color:white;padding:8px 16px;border:0;border-radius:6px;cursor:pointer">Logout</button>
    </form>
</section>
@endauth