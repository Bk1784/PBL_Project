@extends('client.client_dashboard')
@section('content')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detail Pesanan</h1>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold">Produk: {{ $order->product->name }}</h2>
        <p><strong>Jumlah:</strong> {{ $order->quantity }}</p>
        <p><strong>Total Harga:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Catatan:</strong> {{ $order->notes ?? 'Tidak ada catatan' }}</p>
        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
    </div>
</div>

@endsection
