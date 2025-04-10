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
                <img src="{{ asset('upload/product/'.$product->image) }}" 
                        class="w-full h-full object-cover" 
                        alt="{{ $product->name }}">
            </div>
                
                <!-- Detail Produk (70% width) -->
                <div class="w-full lg:w-3/5 xl:w-2/3 p-6">
                    <!-- Header Produk -->
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                        <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" 
                                class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full shadow-md transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13l-1.5-6M7 13h10M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
                            </svg>
                            Tambah ke Keranjang
                        </button>
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
                    
                    <!-- Grid Informasi Produk -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Informasi Produk</h3>
                            <div class="space-y-2">
                                <p><span class="text-gray-500">Kategori:</span> <span class="font-medium">{{ $product->category ?? 'Alat Kantor' }}</span></p>
                                <p><span class="text-gray-500">Berat:</span> <span class="font-medium">0.5 kg</span></p>
                                <p><span class="text-gray-500">Merek:</span> <span class="font-medium">{{ $product->brand ?? 'Merek Premium' }}</span></p>
                                <p><span class="text-gray-500">SKU:</span> <span class="font-medium">PRD-{{ $product->id }}</span></p>
                            </div>
                        </div>
                        
                        <!-- Informasi Pengiriman -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Informasi Pengiriman</h3>
                            @foreach($customer as $custom)
                            <div class="space-y-2">
                                <p><span class="text-gray-500">Dikirim ke:</span> <span class="font-medium">{{ $custom->address }}</span></p>
                                <p><span class="text-gray-500">Estimasi:</span> <span class="font-medium">2-3 hari kerja</span></p>
                                <p><span class="text-gray-500">Pengiriman:</span> <span class="font-medium">Reguler</span></p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function addToCart(id, name, price) {
        fetch("{{ route('cart.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id, name, price })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success); 
            // Bisa ditambahkan update counter keranjang di sini
        });
    }
</script>

@endsection