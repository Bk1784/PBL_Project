<!-- <h1>Detail Order Admin</h1>
<p>Invoice No: {{ $order->invoice_no }}</p>
<p>Nama Customer: {{ $order->name }}</p>

<h3>Items:</h3>
@foreach($order->orderItems as $item)
<p>{{ $item->product->name }} - Qty: {{ $item->qty }} - Harga: {{ $item->price }}</p>
@endforeach -->

<div class="bg-white p-6 rounded-md shadow-md">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Detail Order Admin</h2>

    <div class="mb-2">
        <strong class="text-gray-700">Invoice No:</strong>
        <span class="text-gray-600">{{ $order->invoice_no }}</span>
    </div>

    <div class="mb-4">
        <strong class="text-gray-700">Nama Customer:</strong>
        <span class="text-gray-600">{{ $order->name }}</span>
    </div>

    <h3 class="text-lg font-semibold mb-2 text-gray-800">Item yang Dipesan:</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Produk
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kuantitas
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Harga Satuan
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->product->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->qty }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($item->price, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>