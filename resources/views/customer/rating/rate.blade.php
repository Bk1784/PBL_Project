@extends('dashboard')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg w-full">
    <h3 class="text-2xl font-bold mb-6">Beri Rating untuk Pesanan</h3>

    <!-- Produk Info -->
    <div class="flex items-center space-x-4 mb-6">
        <!-- Foto Produk -->
        <img src="{{ asset($order->product->image) }}" 
             alt="{{ $order->product->name ?? 'Produk' }}" 
             class="w-24 h-24 object-cover rounded-lg shadow">

        <!-- Nama + Invoice -->
        <div>
            <p class="text-lg font-semibold">{{ $order->product->name ?? 'Nama Produk' }}</p>
            <p class="text-gray-500">Invoice: <b>{{ $order->invoice_no }}</b></p>
        </div>
    </div>

    <!-- Form Rating -->
    <form action="{{ route('rating.rate', $order->id) }}" method="POST">
        @csrf

        <!-- Rating Bintang -->
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Rating:</label>
            <div class="flex space-x-2" id="rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <label>
                        <input type="radio" name="rating" value="{{ $i }}" class="hidden">
                        <i class="fa-solid fa-star text-gray-300 cursor-pointer text-3xl"></i>
                    </label>
                @endfor
            </div>
            @error('rating')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Komentar -->
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Komentar:</label>
            <textarea name="comment" rows="4" class="border rounded p-3 w-full"></textarea>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            Kirim Rating
        </button>
    </form>
</div>

<!-- Script interaksi bintang -->
<script>
    const labels = document.querySelectorAll('#rating-stars label');
    labels.forEach((label, index) => {
        const input = label.querySelector('input');
        const star = label.querySelector('i');

        label.addEventListener('click', () => {
            labels.forEach((otherLabel, otherIndex) => {
                const otherStar = otherLabel.querySelector('i');
                otherStar.classList.remove('text-yellow-400');
                otherStar.classList.add('text-gray-300');
                if (otherIndex <= index) {
                    otherStar.classList.add('text-yellow-400');
                }
            });
            input.checked = true;
        });
    });
</script>
@endsection
