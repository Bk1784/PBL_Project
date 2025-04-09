@extends('dashboard')

@section('content')

<!-- Konten Produk -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-3">
        <h2 class="bg-blue-300 text-white text-xl font-bold rounded px-4 py-2 inline-block mb-4">Alat Tulis Kantor</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @isset($product)
                @foreach ($product as $item)
                    <div class="bg-white p-4 rounded-lg shadow-md">
                    <img src="{{ asset('upload/product/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-32 object-contain mb-2" />
                        <h3 class="text-sm font-semibold">{{ $item->name }}</h3>

                        <div class="flex items-center justify-between text-xs mt-1">
                            <span class="text-green-500 font-bold">Tersedia</span>
                            <span class="text-yellow-500">⭐ {{ $item->rating ?? '4.5' }}</span>
                        </div>

                        <p class="text-gray-700 font-bold mt-1">Rp{{ number_format($item->price) }}</p>
                        <p class="text-xs text-gray-500">Terjual: {{ $item->qty }}</p>

                        <div class="flex justify-between mt-3">
                            <button 
                                class="bg-red-500 text-white px-3 py-1 rounded text-sm remove-from-cart"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-price="{{ $item->price }}">
                                -
                            </button>
                            <button 
                                class="bg-blue-500 text-white px-3 py-1 rounded text-sm add-to-cart"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-price="{{ $item->price }}">
                                +
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-sm text-gray-500">Belum ada produk yang ditampilkan.</p>
                
            @endisset
        </div>
    </div>
</div>

@endsection
