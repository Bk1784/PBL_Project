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
                <th class="p-3 text-left">Harga</th>
                <th class="p-3 text-left">Amount</th>
                <th class="p-3 text-left">QTY</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderDate as $key => $item)
            <tr class="hover:bg-gray-100">
                <td class="p-3 border-b border-gray-200">{{ $key + 1 }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->created_at }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->price }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->produk->name }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->qty }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
