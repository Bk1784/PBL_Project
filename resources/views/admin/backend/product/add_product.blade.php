@extends('admin.admin_dashboard')

@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

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

 <!-- Modal Konfirmasi (Sesuai Gambar) -->
 <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Konfirmasi Simpan Perubahan</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyimpan perubahan?</p>
        <div class="flex justify-end gap-3">
            <button id="cancelBtn" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Batal
            </button>
            <button id="confirmBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Ya, Simpan!
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Validasi Form
        $("#product-form").validate({
            rules: {
                name: { required: true },
                qty: { required: true, min: 1 },
                price: { required: true, min: 0.01 },
                image: { required: true }
            },
            messages: {
                name: { required: "Nama produk wajib diisi." },
                qty: { 
                    required: "QTY wajib diisi.",
                    min: "QTY minimal 1." 
                },
                price: { 
                    required: "Harga wajib diisi.",
                    min: "Harga minimal 0.01." 
                },
                image: { required: "Gambar produk wajib diupload." }
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("text-red-500 text-xs");
                error.insertAfter(element);
            },
            highlight: function(element) {
                $(element).addClass("border-red-500");
            },
            unhighlight: function(element) {
                $(element).removeClass("border-red-500");
            },
            submitHandler: function(form) {
                // Tampilkan modal konfirmasi custom
                const modal = $("#confirmModal");
                modal.removeClass("hidden").addClass("flex");

                // Handle tombol "Ya, Simpan!"
                $("#confirmBtn").off("click").on("click", function() {
                    modal.addClass("hidden").removeClass("flex");
                    form.submit(); // Submit form jika dikonfirmasi
                });

                // Handle tombol "Batal"
                $("#cancelBtn").off("click").on("click", function() {
                    modal.addClass("hidden").removeClass("flex");
                });
            }
        });

        // Preview Gambar
        $("#imageInput").change(function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $("#productPreview").attr("src", e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

@endsection