@extends('client.client_dashboard')
@section('content')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Laporan Penjualan</h1>
    <form method="GET" action="{{ route('sales.report') }}" class="mb-4">
        <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-md p-2">
            <option value="most_sold" {{ request('sort') == 'most_sold' ? 'selected' : '' }}>Paling Banyak Dipesan</option>
            <option value="least_sold" {{ request('sort') == 'least_sold' ? 'selected' : '' }}>Paling Sedikit Dipesan</option>
        </select>
    </form>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terjual</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <a href="#" class="product-details" data-id="{{ $product->id }}">{{ $product->name }}</a>
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
    document.querySelectorAll('.product-details').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            const productId = item.getAttribute('data-id');
            fetch(`/product-details/${productId}`)
                .then(response => response.json())
                .then(data => {
                    alert(`Description: ${data.description}\nTotal Sold: ${data.total_sold}\nTotal Revenue: ${data.total_revenue}`);
                });
        });
    });
</script>

@endsection
