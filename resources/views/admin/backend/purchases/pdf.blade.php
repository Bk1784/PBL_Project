<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produk Masuk Bulan {{ $selectedMonth }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #000; padding: 8px; text-align: left;}
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Produk Masuk Bulan {{ $selectedMonth }}</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>QTY</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                    <td>{{ $purchase->product->name }}</td>
                    <td>{{ $purchase->qty }}</td>
                    <td>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
