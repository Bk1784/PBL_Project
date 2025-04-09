@extends('dashboard')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Keranjang Belanja</h2>
    @if(count($cart) > 0)
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Nama Produk</th>
                    <th class="p-2">Jumlah</th>
                    <th class="p-2">Harga</th>
                    <th class="p-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $total = $item['price'] * $item['quantity']; $grandTotal += $total; @endphp
                    <tr>
                        <td class="p-2">{{ $item['name'] }}</td>
                        <td class="p-2">{{ $item['quantity'] }}</td>
                        <td class="p-2">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="p-2">Rp{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold">
                    <td colspan="3" class="p-2 text-right">Total</td>
                    <td class="p-2">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4">
            <a href="#" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Checkout</a>
        </div>
    @else
        <p>Keranjang masih kosong.</p>
    @endif
</div>
@endsection
