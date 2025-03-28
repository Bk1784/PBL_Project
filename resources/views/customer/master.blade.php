<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galaxy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#DBE2EF] font-sans antialiased">



    <!-- Layout Utama -->
    <div class="flex min-h-screen">


        <!-- Konten Utama -->
        <div class="flex-1 max-w-6xl mx-auto mt-10 p-6">
            <!-- Header -->
            <div class="bg-white p-4 flex justify-between items-center rounded-lg shadow-md border border-gray-300">
                <h1 class="text-3xl font-extrabold tracking-wide text-gray-800">GALAXY STORE</h1>
                <div class="flex items-center gap-3">
                    <input type="text" placeholder="🔍 Pencarian Produk"
                        class="px-4 py-2 w-72 rounded-full bg-white text-gray-700 border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition-all duration-300 shadow-sm">
                    <div class="w-10 h-10 bg-gray-400 rounded-full overflow-hidden">
                    <a href="#" class="w-10 h-10 bg-gray-400 rounded-full overflow-hidden block">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
                    </a>
                    </div>
                </div>
            </div>

            <br>

            <div class="flex space-x-4 overflow-x-auto p-4 bg-gray-200 rounded-lg">
            <!-- Produk 1 -->
             <a href="">

                 <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                     <img src="{{ asset('asset/image1.png') }}" alt="Produk 1" class="w-full h-24 object-cover rounded">
                     <h3 class="text-sm font-semibold mt-2">Pulpen Hitam</h3>
                     <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.5)</p>
                     <p class="text-sm font-bold text-blue-500">Rp5.000</p>
                    </div>
                </a>
            
            <!-- Produk 2 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="{{ asset('asset/image2.png') }}" alt="Produk 2" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Buku Catatan</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐☆ (4.2)</p>
                <p class="text-sm font-bold text-blue-500">Rp12.000</p>
            </div>
            
            <!-- Produk 3 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="{{ asset('asset/image3.png') }}" alt="Produk 3" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Pensil</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐⭐ (5.0)</p>
                <p class="text-sm font-bold text-blue-500">Rp25.000</p>
            </div>
            
            <!-- Produk 4 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="{{ asset('asset/image4.png') }}" alt="Produk 4" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Krayon</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                <p class="text-sm font-bold text-blue-500">Rp8.000</p>
            </div>

            <!-- Produk 5 -->
            <div class="w-48 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                <img src="{{ asset('asset/image5.png') }}" alt="Produk 5" class="w-full h-24 object-cover rounded">
                <h3 class="text-sm font-semibold mt-2">Penggaris</h3>
                <p class="text-xs text-gray-500">⭐⭐⭐⭐ (4.0)</p>
                <p class="text-sm font-bold text-blue-500">Rp8.000</p>
            </div>

           
        </div>

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