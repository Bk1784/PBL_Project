@extends('dashboard')
@section('content')
<br>

@php
    $customer = App\Models\User::whereNotNull('address')->where('address', '!=', '')->latest()->get();
   
@endphp

<body class="bg-gray-100 p-4 font-sans">
    <div class="w-full mx-auto px-4">
        <!-- Container utama dengan lebar penuh -->
        <div class="w-full bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Gambar Produk (30% width) -->
                <div class="w-[300px] h-[300px] rounded-full overflow-hidden border-4 border-white shadow-lg">
                <img src="{{ asset($product->image) }}" 
                        class="w-full h-full object-cover" 
                        alt="{{ $product->name }}">
            </div>
                
                <!-- Detail Produk (70% width) -->
                <div class="w-full lg:w-3/5 xl:w-2/3 p-6">
                    <!-- Header Produk -->
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                        <a href="{{ route('add_to_cart', $product->id) }}" class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full shadow-md transition">🧺 Tambah Keranjang</a>
                        
                    </div>
                    
                    <!-- Rating dan Harga -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 text-xl">
                                ⭐⭐⭐⭐⭐
                            </div>
                            <span class="text-gray-500 text-lg ml-2">(4.5)</span>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    
                    <!-- Deskripsi Produk -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Deskripsi Produk</h2>
                        <p class="text-gray-600">{{ $product->description ?? 'Produk berkualitas tinggi dan tahan lama, cocok untuk kebutuhan kantor sehari-hari.' }}</p>
                    </div>
                    
                    <!-- Informasi Stok -->
                    <div class="mb-6">
                        <span class="inline-block px-3 py-2 text-sm font-semibold rounded-lg 
                                    {{ $product->qty > 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $product->qty > 10 ? 'Stok Tersedia' : 'Stok Terbatas' }}: {{ $product->qty }} pcs
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>



@endsection