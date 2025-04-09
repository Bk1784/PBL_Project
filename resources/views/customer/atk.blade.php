@extends('customer.master')

@section('content')

<br>
<br>

<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">

        <div class="flex space-x-4 overflow-x-auto p-4 bg-gray-200 rounded-lg">
            <!-- Produk 1 -->
            <a href="">

                <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                    <img src="{{ asset('asset/image.png') }}" alt="Produk 1" class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">Pulpen Hitam</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.5)</p>
                    <p class="text-sm font-bold text-blue-500">Rp5.000</p>
                </div>
            </a>

            <!-- Produk 2 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="https://via.placeholder.com/100" alt="Produk 2" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Buku Catatan</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.2)</p>
                <p class="text-sm font-bold text-blue-500">Rp12.000</p>
            </div>

            <!-- Produk 3 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="https://via.placeholder.com/100" alt="Produk 3" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Vas Bunga</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐⭐ (5.0)</p>
                <p class="text-sm font-bold text-blue-500">Rp25.000</p>
            </div>

            <!-- Produk 4 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="https://via.placeholder.com/100" alt="Produk 4" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Sticky Notes</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                <p class="text-sm font-bold text-blue-500">Rp8.000</p>
            </div>

            <!-- Produk 5 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="https://via.placeholder.com/100" alt="Produk 5" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Sticky Notes</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                <p class="text-sm font-bold text-blue-500">Rp8.000</p>
            </div>

            <!-- Produk 6 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="https://via.placeholder.com/100" alt="Produk 6" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Sticky Notes</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                <p class="text-sm font-bold text-blue-500">Rp8.000</p>
            </div>
        </div>
    </div>
</body>

@include('customer.dekor')

@endsection