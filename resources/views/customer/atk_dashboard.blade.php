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
            
        @php
            $products = App\Models\Product::latest()->where('status', '1')->get();
        @endphp

        @foreach ($products as $product)
      
            <!-- Produk 1 -->
            <a href="{{route('detail_products',  $product->id) }}" class="cursor-pointer">
                <div
                    class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none hover:scale-105 hover:shadow-lg transform transition">
                    <img src="{{ $product->image }}" alt="Produk 1" class="w-full h-24 object-cover rounded">
                    <h3 class="text-sm font-semibold mt-2">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.5)</p>
                    <p class="text-sm font-bold text-blue-500">{{ $product->price }}</p>
                </div>
            </a>

        @endforeach

        </div>
    </div>
</body>

@include('customer.dekor')

@endsection