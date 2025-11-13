<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-200 pt-12">
    <form action="{{ route('register') }}" method="post" class="w-full max-w-xl">
        @csrf
        <div class="flex bg-white rounded-2xl shadow-lg overflow-hidden w-[600px] mx-auto">
            <div class="w-1/2 p-6 text-center">
                <h2 class="text-2xl font-bold mb-4">Customer Register</h2>
                @if ($errors->any())
                    <ul class="text-red-500 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                @if (Session::has('error'))
                    <p class="text-red-500">{{ Session::get('error') }}</p>
                @endif
                @if (Session::has('success'))
                    <p class="text-green-500">{{ Session::get('success') }}</p>
                @endif
                <div class="space-y-3">
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-user text-gray-600 mr-3"></i>
                        <input type="text" name="name" placeholder="Nama" required class="w-full border-none focus:outline-none p-2">
                    </div>
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-envelope text-gray-600 mr-3"></i>
                        <input type="email" name="email" placeholder="Email" required class="w-full border-none focus:outline-none p-2">
                    </div>
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-lock text-gray-600 mr-3"></i>
                        <input type="password" name="password" placeholder="Password" required class="w-full border-none focus:outline-none p-2">
                    </div>
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-lock text-gray-600 mr-3"></i>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required class="w-full border-none focus:outline-none p-2">
                    </div>
                </div>
                <button type="submit" class="bg-gray-300 text-black font-bold py-3 rounded-xl w-full mt-4 hover:bg-gray-400">Sign Up</button>
            </div>
            <div class="w-1/2 flex justify-center items-center p-4">
                <img src="{{ asset('images/logo.png') }}" alt="Profile Image" class="w-full max-w-[150px] rounded-2xl object-cover">
            </div>
        </div>
    </form>


    <!-- FOOTER -->
    <div class="w-full bg-gray-300 p-6 rounded-lg shadow-md mt-5">
    <div class="flex justify-between flex-wrap md:flex-nowrap">
        <div class="text-center w-full md:w-1/3 mb-4 md:mb-0">
            <h3 class="text-lg font-bold flex justify-center items-center"><i class="fas fa-map-marker-alt text-red-600 mr-2"></i>Alamat</h3>
            <p class="text-sm">Galaxy Store, Srono</p>
        </div>
        <div class="text-center w-full md:w-1/3 mb-4 md:mb-0">
            <h3 class="text-lg font-bold flex justify-center items-center"><i class="fas fa-bullhorn text-red-600 mr-2"></i>Ikuti Kami</h3>
            <p class="text-sm"><a href="#" class="text-blue-500">Instagram</a> <a href="#" class="text-orange-500">Shopee</a></p>
        </div>
        <div class="text-center w-full md:w-1/3">
            <h3 class="text-lg font-bold flex justify-center items-center"><i class="fas fa-phone-alt text-red-600 mr-2"></i>Kontak Kami</h3>
            <p class="text-sm">0812-3456-7890</p>
            <p class="text-sm">0812-9876-5432</p>
        </div>
    </div>
                <!-- Bagian copyright -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-3 text-xs text-gray-600">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo_poliwangi.png') }}" alt="Logo Poliwangi" class="w-8 h-8">
                <span class="font-semibold">Politeknik Negeri Banyuwangi</span>
            </div>
            <span class="hidden md:inline">|</span>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Galaxy Store" class="w-8 h-8">
                <span class="font-semibold">Galaxy Store</span>
            </div>
        </div>
</div>

    <!-- END FOOTER -->
</body>
</html>
