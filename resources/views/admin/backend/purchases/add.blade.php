@extends('admin.admin_dashboard')

@section('content')
<div class="bg-white text-gray-800 p-6 text-sm rounded-lg shadow-md mt-6 border border-gray-300 w-full">

<form action="{{ route('admin.purchases.store') }}" method="POST">
    @csrf

<div class="flex flex-col md:flex-row gap-6">

        <!-- Bagian Form -->
        <div class="w-full md:w-2/3">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Nama Barang</label>
                <select name="product_id"  class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300"> required>
                <option value="">Pilih Barang</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} (Stok: {{ $product->qty }})
                    </option>
                @endforeach
            </select>

            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Jumlah Pembelian</label>
                <input type="number" name="qty" min="1" required
                    class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Total Harga Pembelian (Rp)</label>
                <input type="number" name="total_price" min="0.01" step="0.01"  required
                    class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
            </div>
            <button type="submit"
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                Simpan Produk
            </button>
        </div>
</div>
</form>

</div>


@endsection