@extends('dashboard')

@section('content')

<br><br>

<body class="bg-gray-100 p-6 font-sans">
    <div class="max-w-7xl mx-auto">

        <h2 class="text-lg font-semibold text-white bg-[#8ca9dd] inline-block px-6 py-2 rounded-full mb-6 shadow">
            Alat Tulis Kantor
        </h2>

        @php
            $produk = [
                [
                    'nama' => 'Pulpen Standart',
                    'harga' => 1250,
                    'gambar' => 'image1.png',
                    'merek' => 'Standart AE7',
                    'ukuran' => '0.5 mm',
                    'stok' => 87,
                    'lokasi' => 'Banyuwangi'
                ],
                [
                    'nama' => 'Pulpen Standart',
                    'harga' => 1250,
                    'gambar' => 'image1.png',
                    'merek' => 'Standart AE7',
                    'ukuran' => '0.5 mm',
                    'stok' => 87,
                    'lokasi' => 'Banyuwangi'
                ],
                [
                    'nama' => 'Pulpen Standart',
                    'harga' => 1250,
                    'gambar' => 'image1.png',
                    'merek' => 'Standart AE7',
                    'ukuran' => '0.5 mm',
                    'stok' => 87,
                    'lokasi' => 'Banyuwangi'
                ],
                [
                    'nama' => 'Pulpen Standart',
                    'harga' => 1250,
                    'gambar' => 'image1.png',
                    'merek' => 'Standart AE7',
                    'ukuran' => '0.5 mm',
                    'stok' => 87,
                    'lokasi' => 'Banyuwangi'
                ],
                [
                    'nama' => 'Pulpen Standart',
                    'harga' => 1250,
                    'gambar' => 'image1.png',
                    'merek' => 'Standart AE7',
                    'ukuran' => '0.5 mm',
                    'stok' => 87,
                    'lokasi' => 'Banyuwangi'
                ],
                [
                    'nama' => 'Pulpen Standart',
                    'harga' => 1250,
                    'gambar' => 'image1.png',
                    'merek' => 'Standart AE7',
                    'ukuran' => '0.5 mm',
                    'stok' => 87,
                    'lokasi' => 'Banyuwangi'
                ],
                
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach ($produk as $item)
                <div class="relative p-4 bg-white rounded-2xl shadow-md hover:shadow-xl transform hover:scale-[1.03] transition duration-300">
                    <!-- Tombol keranjang -->
                    <button onclick="addToCart('{{ $loop->index }}', '{{ $item['nama'] }}', '{{ $item['harga'] }}')" class="absolute top-3 right-3 bg-blue-500 text-white p-1.5 rounded-full hover:bg-blue-600 shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13l-1.5-6M7 13h10M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                    </button>

                    <!-- Gambar produk -->
                    <div class="w-full h-28 overflow-hidden flex items-center justify-center bg-gray-50 rounded-md mb-3">
                        <img src="{{ asset('asset/' . $item['gambar']) }}" alt="{{ $item['nama'] }}"
                            class="h-24 object-contain">
                    </div>

                    <!-- Info produk -->
                    <h3 class="text-base font-semibold text-gray-800">{{ $item['nama'] }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $item['merek'] }} • {{ $item['ukuran'] }}</p>
                    <p class="text-xs text-gray-500 mb-1">Kualitas tinggi dan tahan lama, cocok untuk kebutuhan harian kantor.</p>
                    <p class="text-sm font-bold text-blue-600 mb-1">Rp{{ number_format($item['harga'], 0, ',', '.') }}</p>
                    <p class="text-xs text-green-600">Stok: {{ $item['stok'] }} pcs</p>
                    <p class="text-xs text-yellow-500 mb-1">⭐⭐⭐⭐☆ <span class="text-gray-500">(4.5)</span></p>
                    <p class="text-xs text-gray-500">Dikirim dari <strong>{{ $item['lokasi'] }}</strong></p>
                </div>
            @endforeach
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
        });
    }
</script>

@endsection
