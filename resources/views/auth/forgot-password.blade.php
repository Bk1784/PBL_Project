<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<!--  -->
<body class="flex flex-col justify-center items-center h-screen bg-gray-200 m-0">
    
    <form action="{{ route('password.email') }}" method="post" class="w-full max-w-md flex justify-center">
        @csrf
        <div class="flex bg-white rounded-2xl shadow-md overflow-hidden w-[600px] mt-8 items-center justify-center">
            <div class="w-1/2 p-5 text-center">
                <h2 class="text-xl font-bold mb-5">Forget Password</h2>
                <!-- Menampilkan pesan error -->
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="text-red-500">{{ $error }}</p>
                    @endforeach
                @endif
            
                @if (Session::has('error'))
                    <p class="text-red-500">{{ Session::get('error') }}</p>
                @endif
                @if (Session::has('success'))
                    <p class="text-green-500">{{ Session::get('success') }}</p>
                @endif
                <div class="flex items-center border-b border-gray-300 p-2">
                    <i class="fas fa-user text-gray-600 mr-3"></i>
                    <input class="outline-none w-full p-2 bg-transparent" type="email" name="email" placeholder="Email" required>
                </div>
                <button type="submit" class="bg-gray-300 text-black font-bold py-3 px-6 rounded-full w-full mt-4 hover:bg-gray-400"> Reset Link </button>
            </div>
            <div class="w-1/2 flex justify-center items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="Profile Image" class="w-36 h-auto rounded-2xl object-cover">
            </div>
        </div>
    </form>

    <!-- END BODY -->

     
</body>
</html>