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
 
         <!-- Sidebar -->
         <div class="w-64 bg-white shadow-lg p-5 border-r border-gray-300">
             <h2 class="text-base font-bold mb-6 tracking-wide text-gray-800"><img src="{{ asset('images/logo.jpg') }}" width="100" height="100" alt="" class="ml-12"></h2>
             <ul class="space-y-4 text-gray-700">
                 <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                     <span>🏠</span> <span>Dashboard</span>
                 </li>
                 <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                     <span>💳</span> <span>Pembayaran</span>
                 </li>
                 <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                     <span>📦</span> <span>Produk</span>
                 </li>
                 <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                     <span>🎨</span> <span>Dekorasi</span>
                 </li>
                 <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                     <span>⚙️</span> <span>Pengaturan</span>
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
 
             <!-- Kategori Produk -->
             <div class="mt-6">
                 <h2 class="text-xl font-semibold text-gray-700">🖊️ ATK</h2>
                 <div class="grid grid-cols-2 gap-4 mt-2">
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Pensil</button>
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Pulpen</button>
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Spidol</button>
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Kertas</button>
                 </div>
             </div>
 
             <div class="mt-6">
                 <h2 class="text-xl font-semibold text-gray-700">🎨 Dekorasi</h2>
                 <div class="grid grid-cols-2 gap-4 mt-2">
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Buket Bunga</button>
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Buket Uang</button>
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Bingkai Foto</button>
                     <button class="bg-[#DBE2EF] px-4 py-2 rounded-lg shadow-md text-gray-700">Bunga</button>
                 </div>
             </div>
 
             <!-- Keranjang Belanja -->
             <div class="mt-8 p-6 bg-white shadow-md border border-gray-300 rounded-lg">
                 <h2 class="text-xl font-semibold text-gray-700">🛒 Keranjang</h2>
                 <div class="mt-3 space-y-2">
                     <div class="flex justify-between border-b pb-2">
                         <span>Buket Uang 25K, 50 lembar</span>
                         <span class="font-semibold">Rp10.000</span>
                     </div>
                     <div class="flex justify-between border-b pb-2">
                         <span>Foto Ngab Dewa, karton</span>
                         <span class="font-semibold">Rp5.000</span>
                     </div>
                     <div class="flex justify-between">
                         <span>Bingkai Foto 30x65cm, Kayu</span>
                         <span class="font-semibold">Rp45.500</span>
                     </div>
                 </div>
                 <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg mt-4">Checkout</button>
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