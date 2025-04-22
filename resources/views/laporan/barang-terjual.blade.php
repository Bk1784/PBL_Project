<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Terjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

<div class="max-w-5xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-center text-gray-700">Laporan Barang Terjual</h2>

    <form method="GET" class="flex flex-col sm:flex-row items-center gap-4 mb-6">
        <label for="tanggal" class="font-medium">Filter Tanggal:</label>
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Cari</button>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="py-3 px-4 border-b">Tanggal</th>
                    <th class="py-3 px-4 border-b">Nama Barang</th>
                    <th class="py-3 px-4 border-b">Jumlah Terjual</th>
                    <th class="py-3 px-4 border-b">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualan as $item)
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b">{{ $item->tanggal_penjualan }}</td>
                        <td class="py-2 px-4 border-b">{{ $item->barang->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                        <td class="py-2 px-4 border-b">{{ $item->jumlah }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">Tidak ada data penjualan untuk tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
