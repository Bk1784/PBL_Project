@extends('client.client_dashboard')
@section('content')

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Laporan Penjualan</h1>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">Jumlah Terjual</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="bg-white hover:bg-gray-300 cursor-pointer" data-id="{{ $product->id }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $product->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        {{ $product->totalSold() }}
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
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', () => {
            const productId = row.getAttribute('data-id');
            fetch(`/product-details/${productId}`)
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: data.name,
                        html: `
                            <p><strong>Deskripsi:</strong> ${data.description}</p>
                            <p><strong>Harga per Produk:</strong> ${data.price}</p>
                            <p><strong>Jumlah Terjual:</strong> ${data.total_sold}</p>
                            <p><strong>Total Pendapatan:</strong> ${data.total_revenue}</p>
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
</script>

@endsection
