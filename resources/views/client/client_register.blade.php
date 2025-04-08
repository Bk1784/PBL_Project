<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Register Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-200 pt-12">
    <form action="{{ route('client.register.submit') }}" method="post" class="w-full max-w-xl">
        @csrf
        <div class="flex bg-white rounded-2xl shadow-lg overflow-hidden w-[600px] mx-auto">
            <div class="w-1/2 p-6 text-center">
                <h2 class="text-2xl font-bold mb-4">Client Register</h2>
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
                        <input type="text" name="name" placeholder="Nama Toko" required
                            class="w-full border-none focus:outline-none p-2">
                    </div>
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-phone text-gray-600 mr-3"></i>
                        <input type="text" name="phone" placeholder="Nomor HP" required
                            class="w-full border-none focus:outline-none p-2">
                    </div>
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-map-marker-alt text-gray-600 mr-3"></i>
                        <input type="text" name="address" placeholder="Alamat" required
                            class="w-full border-none focus:outline-none p-2">
                    </div>
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-envelope text-gray-600 mr-3"></i>
                        <input type="email" name="email" placeholder="Email" required
                            class="w-full border-none focus:outline-none p-2">
                    </div>
                    <div class="flex items-center border-b border-gray-300 py-2">
                        <i class="fas fa-lock text-gray-600 mr-3"></i>
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full border-none focus:outline-none p-2">
                    </div>
                </div>
                <button type="submit"
                    class="bg-gray-300 text-black font-bold py-3 rounded-xl w-full mt-4 hover:bg-gray-400">Submit</button>
            </div>
            <div class="w-1/2 flex justify-center items-center p-4">
                <img src="{{ asset('images/logo.jpg') }}" alt="Profile Image"
                    class="w-full max-w-[150px] rounded-2xl object-cover">
            </div>
        </div>
    </form>
    <div class="w-full bg-gray-300 p-6 rounded-lg shadow-md mt-5">
        <div class="text-center text-xs text-gray-600 mt-4">© 2025 Galaxy Store - All Rights Reserved.</div>
    </div>
</body>

</html>