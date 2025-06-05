@extends('admin.admin_dashboard')

@section('content')

<!-- Tambahkan script ini di bagian bawah sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Customer Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Produk List</h3>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">s1</th>
                <th class="p-3 text-left">Date</th>
                <th class="p-3 text-left">Invoice</th>
                <th class="p-3 text-left">Amount</th>
                <th class="p-3 text-left">Payment</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderDate as $key => $item)
            <tr class="hover:bg-gray-100">
                <td class="p-3 border-b border-gray-200">{{ $key + 1 }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->created_at }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->invoice_no }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->total_price }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->payment_method }}</td>
                <td class="p-3 border-b border-gray-200">
                    @php
                        $status = strtolower($item->status);
                        $purpleStatuses = ['pending', 'confirm', 'processing', 'delivered'];
                        $greenStatuses = ['selesai', 'completed'];
                    @endphp

                    @if(in_array($status, $purpleStatuses))
                        <span class="inline-block px-3 py-1 rounded-full bg-purple-500 text-white text-sm">{{ $item->status }}</span>
                    @elseif(in_array($status, $greenStatuses))
                        <span class="inline-block px-3 py-1 rounded-full bg-green-500 text-white text-sm">{{ $item->status }}</span>
                    @else
                        <span class="inline-block px-3 py-1 rounded-full bg-gray-400 text-white text-sm">{{ $item->status }}</span>
                    @endif
                </td>
                <td class="p-3 border-b border-gray-200 flex gap-2 items-center">
                    <a href="{{ route('admin.order.details', $item->id) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition duration-200">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
