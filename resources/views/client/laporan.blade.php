@extends('client.client_dashboard')
@section('content')
<div class="container">
    <h1>Laporan Penjualan</h1>
    <form method="GET" action="{{ route('sales.report') }}">
        <select name="sort" onchange="this.form.submit()">
            <option value="most_sold" {{ request('sort') == 'most_sold' ? 'selected' : '' }}>Paling Banyak Dipesan</option>
            <option value="least_sold" {{ request('sort') == 'least_sold' ? 'selected' : '' }}>Paling Sedikit Dipesan</option>
        </select>
    </form>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">Nama Produk</th>
                    <th class="text-center">Jumlah Terjual </th>
                    <th class="text-center">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="align-middle"><a href="#" class="product-details" data-id="{{ $product->id }}">{{ $product->name }}</a></td>
                    <td class="text-center align-middle">{{ $product->totalSold() }}</td>
                    <td class="text-center align-middle">{{ $product->totalRevenue() }}</td>
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
