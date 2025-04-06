@extends('customer.customer_dashboard')

@section('content')

<div class="bg-white text-gray-800 p-6 text-sm rounded-lg shadow-md mt-6 border border-gray-300 w-full">

    <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Profil Customer</h3>

    <form id="edit-profile-form" action="{{ route('customer.profile.update') }}" method="POST"
        enctype="multipart/form-data">
        @method('PUT')
        @csrf

        @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Foto Profil -->
            <div class="w-full md:w-1/3 text-center">
                <img id="profilePreview"
                    src="{{ $customer->photo ? asset('storage/' . $customer->photo) : asset('default-avatar.png') }}"
                    alt="Foto Profil" class="w-36 h-36 rounded-full mx-auto border-2 border-gray-300">

                <input type="file" name="photo" id="photoInput"
                    class="mt-4 block w-full text-sm text-gray-600 border border-gray-300 rounded p-2"
                    onchange="previewImage(event)">
            </div>

            <!-- Form Input -->
            <div class="w-full md:w-2/3">

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nama Lengkap</label>
                    <input type="text" name="nama"
                        class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300"
                        value="{{ old('nama', $customer->nama) }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Username</label>
                    <input type="text" name="username" class="block w-full border border-gray-300 rounded p-2"
                        value="{{ old('username', $customer->username) }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" class="block w-full border border-gray-300 rounded p-2"
                        value="{{ old('email', $customer->email) }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Kontak</label>
                    <input type="text" name="kontak" class="block w-full border border-gray-300 rounded p-2"
                        value="{{ old('kontak', $customer->kontak) }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Alamat</label>
                    <input type="text" name="alamat" class="block w-full border border-gray-300 rounded p-2"
                        value="{{ old('alamat', $customer->alamat) }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="block w-full border border-gray-300 rounded p-2">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki"
                            {{ old('jenis_kelamin', $customer->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin', $customer->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Bio</label>
                    <textarea name="bio" class="block w-full border border-gray-300 rounded p-2"
                        rows="3">{{ old('bio', $customer->bio) }}</textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <!-- Preview Foto JavaScript -->
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