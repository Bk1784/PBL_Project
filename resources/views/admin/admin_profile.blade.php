<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .content {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .profile-card {
            background: rgb(251, 251, 251);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px; /* Agar ukurannya tidak terlalu besar */
            width: 100%;
        }
        .edit-btn {
            background-color: #adb5bd;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.5s;
        }
        .edit-btn:hover {
            background-color: #6c757d;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white p-5 fixed h-full shadow-md">
            <h2 class="text-xl font-bold mb-5">Galaxy Store</h2>
            <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-home mr-2"></i>Dashboard
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-wallet mr-2"></i>Pembayaran
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-box mr-2"></i>Produk
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-paint-brush mr-2"></i>Dekorasi
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-cog mr-2"></i>Pengaturan
            </a>
            <a href="{{ route('admin.logout') }}" class="block py-2 px-4 text-red-500 hover:bg-red-100 rounded">
                <i class="fa fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
<body>


        <!-- Content -->
        <div class="ml-64 p-6 w-full">
            <div class="container mx-auto">
            <div class="bg-white p-8 rounded-lg shadow-md flex items-center gap-8">
            <!-- Profile Picture -->
            <div>
                <img src="{{ asset('storage/' . $admin->photo) }}" alt="Profile Picture" class="rounded-full w-32 h-32 object-cover shadow-md">
            </div>
            <!-- Admin Info -->
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">{{ $admin->name }}</h2>
                <p class="text-gray-700"><strong>Email:</strong> {{ $admin->email }}</p>
                <p class="text-gray-700"><strong>Contact:</strong> {{ $admin->phone }}</p>
                <p class="text-gray-700"><strong>Address:</strong> {{ $admin->address }}</p>
                <p class="text-gray-700"><strong>Bio:</strong> {{ $admin->bio }}</p>
            </div>
            <!-- Edit Button -->
            <div>
                <a href="{{ route('admin.edit.profile') }}" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 transition">EDIT</a>
    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="profile-card">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('storage/' . $admin->photo) }}" alt="Profile Picture" class="rounded-circle" width="150">
                            </div>
                            <div class="col-md-8">
                                <h2><?= $admin->name ?></h2>
                                <p><strong>Name:</strong> <?= $admin->name ?></p>
                                <p><strong>Email:</strong> <?= $admin->email ?></p>
                                <p><strong>Contact:</strong> <?= $admin->phone ?></p>
                                <p><strong>Address:</strong> <?= $admin->address ?></p>
                                <p><strong>Gender:</strong> <?= $admin->gender ?? 'Tidak Diketahui' ?></p>
                                <p><strong>Status:</strong> <?= $admin->status ?></p>
                                <p><strong>Bio:</strong> <?= $admin->bio ?></p>
                                <a href="{{ route('admin.edit.profile') }}" class="btn edit-btn">EDIT</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile-card mt-3 text-end">
                        <h3>Tanggung Jawab</h3>
                        <p>Menerima pesanan, mengeksekusi pesanan, mengedit produk</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsibility Section -->
        <div class="bg-white p-11 rounded-lg shadow-md mt-11">
            <h3 class="text-xl font-bold mb-2">Tanggung Jawab</h3>
            <p class="text-gray-550">Menerima pesanan, mengeksekusi pesanan, mengedit produk</p>
        </div>
        
    <!-- Footer -->
    <footer class="bg-gray-100 py-6 mt-6">
        <div class="container mx-auto text-center">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h5 class="font-bold">Alamat</h5>
                    <p>Srono, Banyuwangi, Jawa Timur, Indonesia</p>
                </div>
                <div>
                    <h5 class="font-bold">Ikuti Kami</h5>
                    <p>
                        <a href="https://instagram.com" target="_blank" class="text-blue-500 hover:underline"><i class="fab fa-instagram mr-2"></i>Instagram</a> |
                        <a href="https://shopee.co.id" target="_blank" class="text-orange-500 hover:underline">Shopee</a>
                    </p>
                </div>
                <div>
                    <h5 class="font-bold">Kontak Kami</h5>
                    <p>0812345678908</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="https://facebook.com" target="_blank" class="text-blue-400 mx-2"><i class="fab fa-facebook fa-2x"></i></a>
                <a href="https://instagram.com" target="_blank" class="text-pink-400 mx-2"><i class="fab fa-instagram fa-2x"></i></a>
                <a href="https://youtube.com" target="_blank" class="text-red-400 mx-2"><i class="fab fa-youtube fa-2x"></i></a>
            </div>
            <p class="mt-4 text-gray-500">&copy; 2025 Galaxy Store. Project Based Learning</p>
        </div>
    </footer>

</body>
</html>

