@extends('admin.admin_dashboard')

@section('content')

<div class="bg-white text-gray-800 p-6 text-sm rounded-lg shadow-md mt-6 border border-gray-300 w-full">

    <h3 class="text-xl font-bold text-gray-800 mb-4">Add Product</h3>

    <form id="product-form" action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Bagian Foto Produk -->
            <div class="w-full md:w-1/3 text-center">
                <img id="productPreview" src="#"
                    alt="Product Image"
                    class="w-36 h-36 rounded-full mx-auto border-2 border-gray-300 object-cover">

                <input type="file" name="image" id="imageInput"
                    class="mt-4 block w-full text-sm text-gray-600 border border-gray-300 rounded p-2"
                    accept="image/jpeg,image/png,image/jpg">
                <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG | Max: 2MB</p>
            </div>

            <!-- Bagian Form -->
            <div class="w-full md:w-2/3">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nama</label>
                    <input type="text" name="name" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">QTY</label>
                    <input type="number" name="qty" min="1" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Price</label>
                    <input type="number" name="price" min="0.01" step="0.01" required
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

<script>
    // Preview image
    document.getElementById('imageInput').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('productPreview').src = e.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Form validation
    document.getElementById('product-form').addEventListener('submit', function(e) {
        const imageInput = document.getElementById('imageInput');
        if (imageInput.files.length === 0) {
            e.preventDefault();
            alert('Please select an image');
            return false;
        }
        return true;
    });
</script>

@endsection