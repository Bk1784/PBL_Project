@extends('client.client_dashboard')
@section('content')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detail Pesanan</h1>
    
    <div class="bg-white rounded-lg shadow p-6">
        <p><strong>Invoice No:</strong> {{ $order->invoice_no }}</p>
        <p><strong>Nama Client:</strong> {{ Auth::guard('client')->user()->name }}</p>

        <h3 class="text-lg font-semibold mt-4 mb-2">Items:</h3>
        <ul class="list-disc list-inside">
            @foreach($order->orderItems as $item)
            <li>{{ $item->product->name }} - Qty: {{ $item->qty }} - Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</li>
            @endforeach
        </ul>

        @php
            $statusClasses = [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'confirmed' => 'bg-blue-100 text-blue-800',
                'processing' => 'bg-indigo-100 text-indigo-800',
                'delivered' => 'bg-purple-100 text-purple-800',
                'completed' => 'bg-green-100 text-green-800',
                'cancelled' => 'bg-red-100 text-red-800',
            ];
            $statusClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800';
        @endphp
        <p class="mt-4"><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">{{ ucfirst($order->status) }}</span></p>
        <p><strong>Catatan:</strong> {{ $order->notes ?? 'Tidak ada catatan' }}</p>
        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
    </div>
</div>

@endsection
