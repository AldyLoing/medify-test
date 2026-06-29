<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $item->nama }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #333; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; padding-top: 20px; }
        .detail { margin-bottom: 20px; }
        .detail td { border: none; padding: 4px 8px; }
    </style>
</head>
<body>
    <h2>Detail Master Item</h2>

    <table class="detail">
        <tr><td><strong>Kode</strong></td><td>:</td><td>{{ $item->kode }}</td></tr>
        <tr><td><strong>Nama</strong></td><td>:</td><td>{{ $item->nama }}</td></tr>
        <tr><td><strong>Supplier</strong></td><td>:</td><td>{{ $item->supplier }}</td></tr>
        <tr><td><strong>Jenis</strong></td><td>:</td><td>{{ $item->jenis }}</td></tr>
        <tr><td><strong>Harga Beli</strong></td><td>:</td><td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td></tr>
        <tr><td><strong>Laba</strong></td><td>:</td><td>{{ $item->laba }}%</td></tr>
        <tr><td><strong>Harga Jual</strong></td><td>:</td><td>{{ number_format($item->harga_beli + ($item->harga_beli * $item->laba / 100), 0, ',', '.') }}</td></tr>
        <tr><td><strong>Kategori</strong></td><td>:</td><td>{{ $item->kategoriItems->pluck('nama')->implode(', ') ?: '-' }}</td></tr>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i:s') }}
    </div>
</body>
</html>
