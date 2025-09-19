@extends('client.client_dashboard')
@section('content')

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Laporan Penjualan</h1>
        <button id="downloadReport" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
            Download Laporan
        </button>
    </div>
    <style>
        table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        th, td {
            border-bottom: 1px solid #e5e7eb;
        }
        thead th {
            border-bottom: 2px solid #a0aec0;
        }
        tbody tr:hover {
            background-color: #e0f2fe !important;
            transition: background 0.2s;
        }
        .detail-btn {
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .detail-btn:active {
            transform: scale(0.97);
            box-shadow: 0 2px 8px rgba(59,130,246,0.2);
        }
    </style>
    <div class="bg-white rounded-lg shadow overflow-x-auto border border-gray-300">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">Detail</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $index => $product)
                <tr class="bg-white hover:bg-gray-300" data-id="{{ $product->id }}">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $product->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        <button class="detail-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded" data-id="{{ $product->id }}">
                            DETAIL
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        {{ $product->totalRevenue() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const productId = btn.getAttribute('data-id');
            fetch(`/product-details/${productId}`)
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: data.name,
                        html: `
                            <table style="width:100%;text-align:left;border-collapse:collapse;">
                                <tr>
                                    <th style="padding:4px 8px;border-bottom:1px solid #eee;">Deskripsi</th>
                                    <td style="padding:4px 8px;border-bottom:1px solid #eee;">${data.description}</td>
                                </tr>
                                <tr>
                                    <th style="padding:4px 8px;border-bottom:1px solid #eee;">Harga per Produk</th>
                                    <td style="padding:4px 8px;border-bottom:1px solid #eee;">${data.price}</td>
                                </tr>
                                <tr>
                                    <th style="padding:4px 8px;border-bottom:1px solid #eee;">Jumlah Terjual</th>
                                    <td style="padding:4px 8px;border-bottom:1px solid #eee;">${data.total_sold}</td>
                                </tr>
                                <tr>
                                    <th style="padding:4px 8px;">Total Pendapatan</th>
                                    <td style="padding:4px 8px;">${data.total_revenue}</td>
                                </tr>
                            </table>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Tutup',
                        background: '#ffffff',
                        backdrop: `
                            rgba(0,0,0,0.4)
                        `
                    });
                });
        });
    });

    document.getElementById('downloadReport').addEventListener('click', function() {
        let csv = 'No,Nama Produk,Deskripsi,Harga per Produk,Jumlah Terjual,Total Pendapatan\n';
        @foreach($products as $index => $product)
            csv += `"{{ $index + 1 }}","{{ $product->name }}","{{ str_replace('"', '""', $product->description) }}","{{ $product->price }}","{{ $product->totalSold() }}","{{ $product->totalRevenue() }}"\n`;
        @endforeach
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'laporan_penjualan.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    });
</script>

@endsection
