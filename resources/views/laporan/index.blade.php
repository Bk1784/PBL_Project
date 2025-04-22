<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Sering Terjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <div class="max-w-4xl mx-auto mt-16 p-8 bg-white rounded-xl shadow-lg">
        <h1 class="text-3xl font-semibold text-center text-gray-800 mb-8">Laporan Barang Sering Terjual</h1>

        <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="px-6 py-4 text-left">Nama Produk</th>
                    <th class="px-6 py-4 text-left">Jumlah Terjual</th>
                    <th class="px-6 py-4 text-left">Harga</th>
                    <th class="px-6 py-4 text-left">Total Pendapatan</th>
                    <th class="px-6 py-4 text-left">Tanggal Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 border-b text-gray-700">{{ $product->name }}</td>
                        <td class="px-6 py-4 border-b text-gray-700">{{ $product->sold_count }}</td>
                        <td class="px-6 py-4 border-b text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 border-b text-gray-700">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 border-b text-gray-700">{{ $product->created_at->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6 text-center">
            <a href="{{ route('admin.product.report') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Refresh</a>
        </div>
    </div>

</body>
</html>
