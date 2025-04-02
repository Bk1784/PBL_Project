<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Galaxy Store</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Custom styling for toggle switch */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #10B981;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10B981;
        }
    </style>
</head>
<body class="bg-[#DBE2EF] font-sans antialiased">

    <!-- Layout Utama -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg p-5 border-r border-gray-300">
            <h2 class="text-base font-bold mb-6 tracking-wide text-gray-800"><img src="{{ asset('images/logo.jpg') }}" width="100" height="100" alt="" class="ml-12"></h2>
            <ul class="space-y-4 text-gray-700">
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🏠</span> <span>Dashboard</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🤝</span> <a href="{{ route('admin.manage_client') }}">Manage Client</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>💳</span> <span>Pembayaran</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>📦</span> <a href="{{ route('admin.all.product') }}">Manage Produk</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🎨</span> <span>Dekorasi</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>⚙️</span> <span>Pengaturan</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>👤</span> <a href="{{ route('admin.profile') }}">Profile</a>
                </li>
                <li class="flex items-center gap-3 text-red-500 hover:text-red-400 transition-all cursor-pointer">
                    <span>🚪</span> <a href="{{ route('admin.logout') }}">Log Out</a>
                </li>
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 max-w-6xl mx-auto mt-10 p-6">
            <!-- Header -->
            <div class="bg-white p-4 flex justify-between items-center rounded-lg shadow-md border border-gray-300">
                <h1 class="text-3xl font-extrabold tracking-wide text-gray-800">GALAXY STORE</h1>
                <div class="flex items-center gap-3">
                    <input type="text" placeholder="🔍 Pencarian Produk"
                        class="px-4 py-2 w-72 rounded-full bg-white text-gray-700 border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition-all duration-300 shadow-sm">
                    <div class="w-10 h-10 bg-gray-400 rounded-full overflow-hidden">
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-gray-400 rounded-full overflow-hidden block">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
                    </a>
                    </div>
                </div>
            </div>

            <!-- Konten yang akan berubah -->
            @yield('content')

            <!-- Footer -->
            <div class="bg-white text-gray-800 p-6 flex justify-between text-sm rounded-lg shadow-md mt-6 border border-gray-300">
                <div>
                    <h2 class="font-semibold text-lg">📍 Alamat</h2>
                    <p class="text-gray-700">Galaxy Store, Srono</p>
                </div>
                <div>
                    <h2 class="font-semibold text-lg">📢 Ikuti Kami</h2>
                    <p class="text-blue-500">Instagram</p>
                    <p class="text-orange-500">Shopee</p>
                </div>
                <div>
                    <h2 class="font-semibold text-lg">📞 Kontak Kami</h2>
                    <p class="text-gray-700">0812-3456-7890</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>