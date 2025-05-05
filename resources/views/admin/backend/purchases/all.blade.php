

@extends('admin.admin_dashboard')

@section('content')

<!--  -->
<!-- Tambahkan script ini di bagian bawah sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Customer Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Produk Masuk</h3>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Tanggal</th>
                <th class="p-3 text-left">Nama Barang</th>
                <th class="p-3 text-left">QTY</th>
                <th class="p-3 text-left">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $key=> $purchase)
            <tr class="hover:bg-gray-100">
                <td class="p-3 border-b border-gray-200">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                <td class="p-3 border-b border-gray-200">{{ $purchase->product->name }}</td>
                <td class="p-3 border-b border-gray-200">{{ $purchase->qty }}</td>
                <td class="p-3 border-b border-gray-200">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection