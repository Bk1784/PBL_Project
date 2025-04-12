@extends('dashboard')
@section('content')
<br>
<br>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content (Left Side) -->
    <div class="flex-1">
        <div class="flex space-x-4 overflow-x-auto p-4 bg-gray-200 rounded-lg">
            @php
                $products = App\Models\Product::latest()->where('status', '1')->get();
            @endphp

            <div class="grid grid-cols-3 gap-3 w-full">
                @foreach ($products as $product)
                <div class="p-2 bg-white rounded-lg shadow hover:scale-105 hover:shadow-md transform transition">
                    <a href="{{ route('detail_products', $product->id) }}" class="cursor-pointer block h-full">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-20 object-cover rounded">
                        <h3 class="text-xs font-semibold mt-1 line-clamp-1">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.5)</p>
                        <p class="text-xs font-bold text-blue-500">{{ $product->price }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Bar -->
    <div class="w-full lg:w-80 bg-white p-5 border-t lg:border-l border-gray-300">
        <div class="right-bar-content">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Informasi</h2>
            
            <!-- Daftar Produk di Keranjang -->
            <div class="mb-6 space-y-3 border-b pb-4">
                <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    Pesanan Anda <span id="cart-count">{{ count((array) session('cart')) }}</span> Item
                </h3>

                <div id="cart-items">
                    @php $total = 0; $totalItems = 0; @endphp
                    @if(session('cart'))
                        @foreach(session('cart') as $id => $details)
                            @php
                                $total += $details['price'] * $details['qty'];
                                $totalItems += $details['qty'];
                            @endphp
                            <div class="cart-item flex justify-between items-center py-2" id="cart-item-{{ $id }}">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" width="50" height="50" class="object-cover">
                                    <div>
                                        <h3 class="font-semibold">{{ $details['name'] }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded dec" data-id="{{ $id }}">
                                                -
                                            </button>
                                            <span class="quantity w-6 text-center">{{ $details['qty'] }}</span>
                                            <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded inc" data-id="{{ $id }}">
                                                +
                                            </button>
                                            <button class="text-red-500 hover:text-red-700 ml-2 remove-item" data-id="{{ $id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span class="font-semibold" id="item-price-{{ $id }}">Rp{{ $details['price'] * $details['qty'] }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">Keranjang Anda kosong</p>
                    @endif
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="mb-6">
                <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    <span>🛒</span> Ringkasan Keranjang
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Total Items:</span>
                        <span id="total-items">{{ $totalItems }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Harga:</span>
                        <span class="font-bold" id="total-price">Rp{{ $total }}</span>
                    </div>
                    <a href="{{ route('customer.checkout.view_checkout') }}">
                        <button class="w-full bg-blue-500 text-white py-2 rounded-lg mt-2 hover:bg-blue-600 transition-colors">
                            Checkout
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection