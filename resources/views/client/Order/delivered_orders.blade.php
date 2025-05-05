@extends('client.client_dashboard')

@section('content')

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Delivered Orders Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Delivered Orders</h3>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Sl</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Jumlah</th>
                    <th class="p-3 text-left">Pembayaran</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $key => $order)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b border-gray-200">{{ $key + 1 }}</td>
                    <td class="p-3 border-b border-gray-200">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $order->invoice_no }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $order->total_price }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $order->payment_method ?? '-' }}</td>
                    <td class="p-3 border-b border-gray-200">
                        <span
                            class="bg-purple-500 text-white py-1 px-3 rounded-full text-sm">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="p-3 border-b border-gray-200">
                        <form action="{{ route('client.pesanan.complete', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin pesanan ini sudah selesai?');">
                            @csrf
                            <button type="submit" class="bg-green-700 hover:bg-green-800 text-white py-1 px-3 rounded">
                                Selesai
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
