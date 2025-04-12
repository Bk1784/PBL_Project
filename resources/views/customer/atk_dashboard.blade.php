@extends('dashboard')

@section('content')
<br>
<br>

<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-6">
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
    </div>
</body>

@endsection