@extends('admin.admin_dashboard')
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert -->

<div class="bg-white text-gray-800 p-6 text-sm rounded-lg shadow-md mt-6 border border-gray-300 w-full">

    <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Product</h3>

    <form id="product-form" action="{{ route('admin.update.product') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $product->id }}">

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Product Image Section -->
            <div class="w-full md:w-1/3 text-center">
                <img id="productPreview" src="{{ $product->image ? asset('upload/product/' . $product->image) : '#' }}"
                    alt="Product Image"
                    class="w-36 h-36 rounded-full mx-auto border-2 border-gray-300 object-cover">

                <input type="file" name="image" id="imageInput"
                    class="mt-4 block w-full text-sm text-gray-600 border border-gray-300 rounded p-2"
                    accept="image/jpeg,image/png,image/jpg">
                <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG | Max: 2MB</p>
                @if($product->image)
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remove_image" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Remove current image</span>
                        </label>
                    </div>
                @endif
            </div>

            <!-- Form Section -->
            <div class="w-full md:w-2/3">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">QTY</label>
                    <input type="number" name="qty" value="{{ old('qty', $product->qty) }}" min="1" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                    @error('qty')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Price</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0.01" step="0.01" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit"
                        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                        Update Product
                    </button>
                    <a href="#"
                        class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
    $(document).ready(function() {
        // Handle form submission with AJAX
        $('#product-form').submit(function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Show success notification
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    
                    Toast.fire({
                        title: 'Product updated successfully!',
                    });
                    
                    // Optional: Redirect after delay
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.all.product') }}";
                    }, 1500);
                },
                error: function(xhr) {
                    // Show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Failed to update product'
                    });
                }
            });
        });

        // Preview image when selected
        $('#imageInput').change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#productPreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>

@endsection