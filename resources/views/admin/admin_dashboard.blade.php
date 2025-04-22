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
 
     <!-- jQuery Validate -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
     
 
     <style>
         /* Custom styling for toggle switch */
         .toggle-checkbox:checked {
             right: 0;
             border-color: #10B981;
         }
         .toggle-checkbox:checked + .toggle-label {
             background-color: #10B981;
         }
         
         /* Sidebar toggle for mobile */
 
         .sidebar {
             transition: transform 0.3s ease-in-out;
         }
 @@ -72,10 +71,10 @@
         </button>
     </div>
 
     <!-- Overlay for mobile menu -->
     <!-- Overlay -->
     <div id="overlay" class="overlay"></div>
 
     <!-- Layout Utama -->
     <!-- Main Layout -->
     <div class="flex min-h-screen">
         <!-- Sidebar -->
         <div id="sidebar" class="sidebar fixed md:relative z-20 w-64 bg-white shadow-lg p-5 border-r border-gray-300 md:translate-x-0 sidebar-hidden md:sidebar-visible">
 @@ -137,13 +136,24 @@
                 <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                     <span>👤</span> <a href="{{ route('admin.profile') }}" class="w-full">Profile</a>
                 </li>
                 <li>
                     <button id="toggleReports" class="w-full text-left py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2 flex justify-between items-center">
                         <span><i class="fa fa-briefcase mr-2"></i>Manage Reports</span>
                         <i class="fas fa-chevron-down text-sm"></i>
                     </button>
                     <div id="reportsSubmenu" class="ml-6 hidden">
                         <a href="{{ route('admin.all.reports') }}" class="block py-1 px-4 text-gray-600 hover:bg-blue-50 rounded text-sm mb-2">
                             <i class="fa fa-file-alt mr-1"></i>All Reports
                         </a>
                     </div>
                 </li>
                 <li class="flex items-center gap-3 text-red-500 hover:text-red-400 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                     <span>🚪</span> <a href="{{ route('admin.logout') }}" class="w-full">Log Out</a>
                 </li>
             </ul>
         </div>
 
         <!-- Konten Utama -->
         <!-- Main Content -->
         <div class="flex-1 max-w-6xl mx-auto mt-10 p-4 md:p-6 md:ml-0">
             <!-- Header -->
             <div class="bg-white p-4 flex flex-col md:flex-row justify-between items-center rounded-lg shadow-md border border-gray-300 gap-4 md:gap-0">
 @@ -155,16 +165,15 @@
                         <a href="{{ route('admin.profile') }}" class="block">
                             @php
                             $admin = Auth::guard('admin')->user();
                             $photo = $admin && $admin->photo ? 'storage/' . $admin->photo :
                             'profile_photos/default.jpg';
                             $photo = $admin && $admin->photo ? 'storage/' . $admin->photo : 'profile_photos/default.jpg';
                             @endphp
                             <img src="{{ asset($photo) }}" alt="Profile" class="w-full h-full object-cover">
                         </a>
                     </div>
                 </div>
             </div>
 
             <!-- Konten yang akan berubah -->
             <!-- Dynamic Content -->
             @yield('content')
 
             <!-- Footer -->
 @@ -186,65 +195,29 @@
         </div>
     </div>
 
     <!-- Sidebar Script -->
     <script>
         // Toggle submenu
         document.getElementById('manageProductToggle').addEventListener('click', function() {
             const submenu = document.getElementById('manageProductSubmenu');
             if (submenu.classList.contains('hidden')) {
                 submenu.classList.remove('hidden');
             } else {
                 submenu.classList.add('hidden');
             }
         });
 
         // Mobile menu toggle
         document.getElementById('mobileMenuButton').addEventListener('click', function() {
         document.getElementById('mobileMenuButton').addEventListener('click', function () {
             document.getElementById('sidebar').classList.remove('sidebar-hidden');
             document.getElementById('sidebar').classList.add('sidebar-visible');
             document.getElementById('overlay').classList.add('overlay-visible');
         });
 
         document.getElementById('closeSidebar').addEventListener('click', function() {
             document.getElementById('sidebar').classList.remove('sidebar-visible');
         document.getElementById('closeSidebar').addEventListener('click', function () {
             document.getElementById('sidebar').classList.add('sidebar-hidden');
             document.getElementById('overlay').classList.remove('overlay-visible');
         });
 
         document.getElementById('overlay').addEventListener('click', function() {
             document.getElementById('sidebar').classList.remove('sidebar-visible');
             document.getElementById('sidebar').classList.add('sidebar-hidden');
             document.getElementById('overlay').classList.remove('overlay-visible');
         function toggleManageStore() {
             document.getElementById('manageStoreSubmenu').classList.toggle('hidden');
         }
 
         document.getElementById('manageProductToggle').addEventListener('click', function () {
             document.getElementById('manageProductSubmenu').classList.toggle('hidden');
         });
 
         // Close sidebar when clicking on a link (for mobile)
         document.querySelectorAll('#sidebar a').forEach(link => {
             link.addEventListener('click', function() {
                 if (window.innerWidth < 768) {
                     document.getElementById('sidebar').classList.remove('sidebar-visible');
                     document.getElementById('sidebar').classList.add('sidebar-hidden');
                     document.getElementById('overlay').classList.remove('overlay-visible');
                 }
             });
         document.getElementById('toggleReports').addEventListener('click', function () {
             document.getElementById('reportsSubmenu').classList.toggle('hidden');
         });
     </script>
 
 <script>
 function toggleManageStore() {
     const submenu = document.getElementById('manageStoreSubmenu');
     const isExpanded = submenu.getAttribute('aria-expanded') === 'true';
     
     submenu.classList.toggle('hidden');
     submenu.setAttribute('aria-expanded', !isExpanded);
 }
 
 // Optional: Close submenu when clicking elsewhere
 document.addEventListener('click', function(event) {
     if (!event.target.closest('.manageStoreToggle') && !event.target.closest('#manageStoreSubmenu')) {
         const submenu = document.getElementById('manageStoreSubmenu');
         submenu.classList.add('hidden');
         submenu.setAttribute('aria-expanded', 'false');
     }
 });
 </script>
 </body>
 </html>
 </html>

