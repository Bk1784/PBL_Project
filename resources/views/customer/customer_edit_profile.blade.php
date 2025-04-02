@extends('customer.customer_dashboard')

@section('content')

<div class="bg-white text-gray-800 p-6 text-sm rounded-lg shadow-md mt-6 border border-gray-300 w-full">

    <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Profil Customer</h3>

    <form id="edit-profile-form" action="{{ route('customer.update.profile') }}" method="POST"
        enctype="multipart/form-data">
        @method('PUT')
        @csrf

        @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Bagian Foto Profil -->
            <div class="w-full md:w-1/3 text-center">
                <img id="profilePreview"
                    src="{{ $customer->photo ? asset('storage/' . $customer->photo) : asset('default-avatar.png') }}"
                    alt="Profile Picture" class="w-36 h-36 rounded-full mx-auto border-2 border-gray-300">

                <input type="file" name="photo" id="photoInput"
                    class="mt-4 block w-full text-sm text-gray-600 border border-gray-300 rounded p-2"
                    onchange="previewImage(event)">
            </div>

            <!-- Bagian Form -->
            <div class="w-full md:w-2/3">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Username</label>
                    <input type="text" name="name"
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300"
                        value="{{ old('name', $client->name) }}" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email"
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300"
                        value="{{ old('email', $client->email) }}" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Contact</label>
                    <input type="text" name="phone"
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300"
                        value="{{ old('phone', $client->phone) }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Address</label>
                    <input type="text" name="address"
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300"
                        value="{{ old('address', $client->address) }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Bio</label>
                    <textarea name="bio"
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300"
                        rows="3">{{ old('bio', $client->bio) }}</textarea>
                </div>

                <button type="submit" id="save-button"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <script>
    function previewImage(event) {
        const image = document.getElementById('profilePreview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('edit-profile-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Konfirmasi Simpan Perubahan',
                        text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    form.submit();
                }
            });
        }
    });
    </script>
</div>

@endsection