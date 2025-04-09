@extends('dashboard')

@section('content')

<br>
<br>

<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">

        <h2 class="text-base font-semibold text-white bg-[#a0b4e3] inline-block px-6 py-2 rounded-full mb-4">
            Alat Tulis Kantor
        </h2>
        <div class="flex space-x-4 overflow-x-auto p-4 bg-gray-200 rounded-lg">
            <!-- Produk 1 -->
            <a href="customer/product/1" class="cursor-pointer">
                <div
                    class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none hover:scale-105 hover:shadow-lg transform transition">
                    <img src="{{ asset('asset/image1.png') }}" alt="Produk 1" class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">Pulpen Hitam</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.5)</p>
                    <p class="text-sm font-bold text-blue-500">Rp5.000</p>
                </div>
            </a>

            <!-- Produk 2 -->
            <a href="customer/product/2" class="cursor-pointer">
                <div
                    class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none hover:scale-105 hover:shadow-lg transform transition">
                    <img src="{{ asset('asset/image2.png') }}" alt="Produk 2" class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">Buku Catatan</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.2)</p>
                    <p class="text-sm font-bold text-blue-500">Rp12.000</p>
                </div>
            </a>

            <!-- Produk 3 -->
            <a href="customer/product/3" class="cursor-pointer">
                <div
                    class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none hover:scale-105 hover:shadow-lg transform transition">
                    <img src="{{ asset('asset/vasbunga.png') }}" alt="Produk 3"
                        class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">Vas Bunga</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐⭐ (5.0)</p>
                    <p class="text-sm font-bold text-blue-500">Rp25.000</p>
                </div>
            </a>

            <!-- Produk 4 -->
            <a href="customer/product/4" class="cursor-pointer">
                <div
                    class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none hover:scale-105 hover:shadow-lg transform transition">
                    <img src="{{ asset('asset/stickynote.png') }}" alt="Produk 4"
                        class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">Sticky Note</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                    <p class="text-sm font-bold text-blue-500">Rp8.000</p>
                </div>
            </a>

            <!-- Produk 5 -->
            <a href="customer/product/5" class="cursor-pointer">
                <div
                    class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none hover:scale-105 hover:shadow-lg transform transition">
                    <img src="{{ asset('asset/stickynote.png') }}" alt="Produk 5"
                        class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">Sticky Note</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                    <p class="text-sm font-bold text-blue-500">Rp8.000</p>
                </div>
            </a>

            <!-- Produk 6 -->
            <a href="customer/product/6" class="cursor-pointer">
                <div
                    class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none hover:scale-105 hover:shadow-lg transform transition">
                    <img src="{{ asset('asset/stickynote.png') }}" alt="Produk 6"
                        class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">Sticky Note</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                    <p class="text-sm font-bold text-blue-500">Rp8.000</p>
                </div>
            </a>
        </div>
    </div>
</body>

@include('customer.dekor')

@endsection