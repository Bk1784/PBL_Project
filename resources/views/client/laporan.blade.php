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

    <!-- Laporan Refund Table -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold">Laporan Refund</h2>
                <p class="text-sm text-gray-600 mt-1">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Menampilkan refund yang sudah diproses (Diterima/Ditolak)
                    </span>
                </p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-x-auto border border-gray-300">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">Tanggal Refund</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">DETAIL</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($refunds as $index => $refund)
                    <tr class="bg-white hover:bg-gray-300">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $refund->order->product->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ \Carbon\Carbon::parse($refund->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            @if($refund->status === 'accepted')
                                <span class="bg-blue-500 text-white py-1 px-3 rounded-full text-sm">
                                    Diterima
                                </span>
                            @elseif($refund->status === 'rejected')
                                <span class="bg-red-500 text-white py-1 px-3 rounded-full text-sm">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            <button class="refund-detail-btn bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-3 rounded" data-id="{{ $refund->id }}">
                                DETAIL
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data refund yang sudah diproses
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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



    // Handle refund detail buttons
    document.querySelectorAll('.refund-detail-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const refundId = btn.getAttribute('data-id');
            fetch(`/refund-details/${refundId}`)
                .then(response => response.json())
                .then(data => {
                    let htmlContent = `
                        <div style="text-align: left; max-width: 500px; margin: 0 auto;">
                            <!-- Header Info -->
                            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                <h3 style="margin: 0; font-size: 18px; font-weight: bold;">📋 Informasi Refund</h3>
                                <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Detail lengkap permohonan refund</p>
                            </div>

                            <!-- Product Info Card -->
                            <div style="background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                <div style="border-left: 4px solid #007bff; padding-left: 15px; margin-bottom: 15px;">
                                    <h4 style="margin: 0 0 10px 0; color: #007bff; font-size: 16px;">📦 Informasi Produk</h4>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #dee2e6;">
                                    <span style="font-weight: 600; color: #495057;">Nama Produk:</span>
                                    <span style="color: #6c757d; font-weight: 500;">${data.product_name}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0;">
                                    <span style="font-weight: 600; color: #495057;">Customer:</span>
                                    <span style="color: #6c757d; font-weight: 500;">${data.user_name}</span>
                                </div>
                            </div>

                            <!-- Refund Details Card -->
                            <div style="background: #fff3cd; border: 2px solid #ffeaa7; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                <div style="border-left: 4px solid #ffc107; padding-left: 15px; margin-bottom: 15px;">
                                    <h4 style="margin: 0 0 10px 0; color: #856404; font-size: 16px;">🔄 Detail Refund</h4>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #ffeaa7;">
                                    <span style="font-weight: 600; color: #856404;">Jumlah Produk:</span>
                                    <span style="background: #ffc107; color: #212529; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 14px;">${data.refund_qty} unit</span>
                                </div>
                                <div style="padding: 10px 0;">
                                    <span style="font-weight: 600; color: #856404; display: block; margin-bottom: 8px;">Alasan Refund:</span>
                                    <div style="background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #ffeaa7; color: #495057; line-height: 1.5;">${data.refund_reason}</div>
                                </div>
                            </div>

                            <!-- Evidence Section -->
                            ${data.refund_image ? `
                                <div style="background: #d1ecf1; border: 2px solid #bee5eb; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                    <div style="border-left: 4px solid #17a2b8; padding-left: 15px; margin-bottom: 15px;">
                                        <h4 style="margin: 0 0 10px 0; color: #0c5460; font-size: 16px;">📷 Bukti Refund</h4>
                                    </div>
                                    <div style="text-align: center;">
                                        <img src="${data.refund_image}" alt="Bukti Refund" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 3px solid #17a2b8; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);">
                                    </div>
                                </div>
                            ` : ''}

                            <!-- Footer Info -->
                            <div style="background: #d4edda; border: 2px solid #c3e6cb; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                <div style="border-left: 4px solid #28a745; padding-left: 15px; display: inline-block;">
                                    <span style="color: #155724; font-size: 14px; font-weight: 500;">📅 Tanggal: ${data.created_at}</span>
                                </div>
                            </div>
                        </div>
                    `;

                    Swal.fire({
                        title: 'Detail Refund',
                        html: htmlContent,
                        icon: 'info',
                        confirmButtonText: 'Tutup',
                        background: '#ffffff',
                        backdrop: `rgba(0,0,0,0.4)`,
                        width: '600px',
                        padding: '20px',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal memuat detail refund',
                        icon: 'error',
                        confirmButtonText: 'Tutup'
                    });
                });
        });
    });
</script>

@endsection
