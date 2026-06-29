<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategori {{ $kategori->nama }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #333; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; padding-top: 20px; }
    </style>
</head>
<body>
    <h2>Kategori: {{ $kategori->nama }}</h2>
    <p>Kode: {{ $kategori->kode }}</p>

    <h4>Daftar Item</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Supplier</th>
                <th>Harga Beli</th>
                <th>Laba</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($kategori->masterItems as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td>{{ $item->laba }}%</td>
                <td>{{ number_format($item->harga_beli + ($item->harga_beli * $item->laba / 100), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i:s') }}
    </div>
</body>
</html>
