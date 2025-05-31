@extends('admin.admin_dashboard')

@section('content')

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Detail Order Admin</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-600 font-medium">Invoice No:</p>
            <p class="text-gray-800 font-semibold">{{ $order->invoice_no }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-600 font-medium">Nama Customer:</p>
            <p class="text-gray-800 font-semibold">{{ $order->name }}</p>
        </div>
    </div>

    <h3 class="text-xl font-semibold mb-4 text-gray-800">Items:</h3>
    
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Produk</th>
                    <th class="p-3 text-left">Qty</th>
                    <th class="p-3 text-left">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr class="hover:bg-gray-50 border-b border-gray-200">
                    <td class="p-3">{{ $item->product->name }}</td>
                    <td class="p-3">{{ $item->qty }}</td>
                    <td class="p-3">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection