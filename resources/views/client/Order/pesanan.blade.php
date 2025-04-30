@extends('client.client_dashboard')
@section('content')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Daftar Pesanan</h1>
    
    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500">Belum ada pesanan</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('client.pesanan.details', $order->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ asset('upload/product/'.$order->product->image) }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->product->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('client.pesanan.cancel', $order->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                        Batalkan
                                    </button>
                                </form>
                                @elseif($order->status == 'delivered')
                                <form method="POST" action="{{ route('client.pesanan.execute', $order->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                        Terima
                                    </button>
                                </form>
                                @elseif($order->status == 'completed')
                                <span class="text-green-600 font-semibold">Selesai</span>
                                @else
                                <span class="text-gray-600 font-semibold">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@endsection
