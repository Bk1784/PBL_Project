@extends('admin.admin_dashboard')
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="bg-white text-gray-800 p-6 text-sm rounded-lg shadow-md mt-6 border border-gray-300 w-full">

    <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Decoration</h3>

    <form id="decoration-form" action="{{ route('admin.update.decoration', $decoration->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if (session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Decoration Image Section -->
            <div class="w-full md:w-1/3 text-center">
                <img id="productPreview" src="{{ $decoration->image ? asset($decoration->image) : '#' }}"
                    alt="Decoration Image" class="w-36 h-36 rounded-full mx-auto border-2 border-gray-300 object-cover">

                <input type="file" name="image" id="imageInput"
                    class="mt-4 block w-full text-sm text-gray-600 border border-gray-300 rounded p-2"
                    accept="image/jpeg,image/png,image/jpg">
                <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG | Max: 2MB</p>

                @if($decoration->image)
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remove_image"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Remove current image</span>
                    </label>
                </div>
                @endif
            </div>

            <!-- Form Section -->
            <div class="w-full md:w-2/3">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name', $decoration->name) }}" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">QTY</label>
                    <input type="number" name="qty" value="{{ old('qty', $decoration->qty) }}" min="1" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Price</label>
                    <input type="number" name="price" value="{{ old('price', $decoration->price) }}" min="0.01"
                        step="0.01" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Deskripsi</label>
                    <textarea name="description" id="description" maxlength="65535" rows="5"
                        oninput="updateCharCounter()" required
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300 h-32">{{ old('description', $decoration->description) }}</textarea>
                    <small class="text-muted"><span id="charCount">0</span>/65535 characters</small>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                        Update Decoration
                    </button>
                    <a href="{{ route('admin.all.decoration') }}"
                        class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
function updateCharCounter() {
    const textarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const currentLength = textarea.value.length;
    charCount.textContent = currentLength;
    charCount.style.color = (currentLength > 60000) ? 'red' : '';
}

document.addEventListener('DOMContentLoaded', function() {
    updateCharCounter();
});

$(document).ready(function() {
    // Handle form submission with AJAX
    $('#decoration-form').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(function() {
                    window.location.href = "{{ route('admin.all.decoration') }}";
                }, 2000);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'Failed to update decoration'
                });
            }
        });
    });

    // Preview image
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