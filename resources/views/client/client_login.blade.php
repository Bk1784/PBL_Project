<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<!-- BODY -->

<body class="flex flex-col justify-center items-center h-screen bg-gray-200">
    <form action="{{ route('client.login_submit') }}" method="post" class="w-full max-w-lg">
        @csrf
        <div class="flex bg-white rounded-2xl shadow-lg overflow-hidden w-full">
            <div class="w-1/2 p-6 text-center">
                <h2 class="text-lg font-bold mb-4">LOGIN</h2>

                @if ($errors->any())
                <ul class="text-red-500 text-sm mb-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif

                @if (Session::has('error'))
                <p class="text-red-500 text-sm">{{ Session::get('error') }}</p>
                @endif
                @if (Session::has('success'))
                <p class="text-green-500 text-sm">{{ Session::get('success') }}</p>
                @endif

                <div class="flex items-center border-b border-gray-300 py-2 mb-3">
                    <i class="fa-solid fa-envelope text-gray-500 mr-2 "></i>
                    <input class="w-full outline-none bg-transparent px-2" type="email" name="email" placeholder="Email"
                        required>
                </div>
                <div class="flex items-center border-b border-gray-300 py-2 mb-3">
                    <i class="fas fa-lock text-gray-500 mr-2"></i>
                    <input class="w-full outline-none bg-transparent px-2" type="password" name="password"
                        placeholder="Password" required>
                </div>

                <button type="submit"
                    class="w-full bg-gray-300 text-black py-2 rounded-full font-bold mt-2 hover:bg-gray-400">Submit</button>
                <p class="text-gray-500 text-sm my-3">ATAU</p>
                <p class="text-sm font-bold"><a href="{{ route('client.register') }}" class="text-black">DAFTAR</a>
                    tidak memiliki akun</p>
                <a href="{{ route('client.forget_password') }}" class="text-blue-500 text-sm underline">Forget
                    Password?</a>
            </div>
            <div class="w-1/2 flex justify-center items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="Profile Image"
                    class="w-36 h-auto rounded-2xl object-cover">
            </div>
        </div>
    </form>

    <!-- END BODY -->

    <!-- FOOTER -->
    <div class="w-full bg-gray-300 p-6 rounded-lg shadow-md mt-5">
        <div class="flex justify-between flex-wrap md:flex-nowrap">
            <div class="text-center w-full md:w-1/3 mb-4 md:mb-0">
                <h3 class="text-lg font-bold flex justify-center items-center"><i
                        class="fas fa-map-marker-alt text-red-600 mr-2"></i>Alamat</h3>
                <p class="text-sm">Galaxy Store, Srono</p>
            </div>
            <div class="text-center w-full md:w-1/3 mb-4 md:mb-0">
                <h3 class="text-lg font-bold flex justify-center items-center"><i
                        class="fas fa-bullhorn text-red-600 mr-2"></i>Ikuti Kami</h3>
                <p class="text-sm"><a href="#" class="text-blue-500">Instagram</a> <a href="#"
                        class="text-orange-500">Shopee</a></p>
            </div>
            <div class="text-center w-full md:w-1/3">
                <h3 class="text-lg font-bold flex justify-center items-center"><i
                        class="fas fa-phone-alt text-red-600 mr-2"></i>Kontak Kami</h3>
                <p class="text-sm">0812-3456-7890</p>
                <p class="text-sm">0812-9876-5432</p>
            </div>
        </div>
        <div class="text-center text-xs text-gray-600 mt-4">© 2025 Galaxy Store - All Rights Reserved.</div>
    </div>

    <!-- END FOOTER -->

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @if(Session::has('message'))
    <script>
    var type = "{{ Session::get('alert-type','info') }}"
    switch (type) {
        case 'info':
            toastr.info(" {{ Session::get('message') }} ");
            break;

        case 'success':
            toastr.success(" {{ Session::get('message') }} ");
            break;

        case 'warning':
            toastr.warning(" {{ Session::get('message') }} ");
            break;

        case 'error':
            toastr.error(" {{ Session::get('message') }} ");
            break;
    }
    </script>
    @endif


</body>

</html>