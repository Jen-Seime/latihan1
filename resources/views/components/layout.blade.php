<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Todo Manager' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f2f5ec; color: #142d22; font: 16px/1.6 Arial, sans-serif; }
        main { max-width: 1100px; margin: auto; padding: 24px; }
        header { background: #f9d64b; border: 2px solid #145a3b; border-radius: 16px; padding: 24px; }
        h1 { font-size: 28px; margin: 8px 0; }
        .badge { display: inline-block; background: #145a3b; color: white; padding: 5px 12px; border-radius: 20px; }
        .filters { display: flex; gap: 12px; flex-wrap: wrap; align-items: end; margin: 24px 0; }
        label { display: block; font-weight: bold; }
        input, select, button, .button { font: inherit; padding: 10px; border-radius: 7px; border: 1px solid #b6c9b6; }
        input, select { max-width: 100%; }
        button, .button { background: #145a3b; color: white; cursor: pointer; text-decoration: none; display: inline-block; }
        a { color: #145a3b; }
        .table-wrap { overflow-x: auto; background: white; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #dce5db; text-align: left; }
        th { background: #145a3b; color: white; }
        .actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .actions form { margin: 0; }
        .danger { background: #a52b29; }
        .notice { background: white; border-left: 5px solid #145a3b; padding: 12px; margin: 12px 0; }
        nav { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; margin: 20px 0; }
        :focus-visible { outline: 3px solid #b36b00; outline-offset: 3px; }
        @media (max-width: 600px) { main { padding: 14px; } h1 { font-size: 23px; } }
    </style>
</head>
<body class="bg-amber-50 text-slate-800">
    {{ $slot }}
</body>
</html>