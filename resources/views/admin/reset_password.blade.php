<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="flex flex-col justify-center items-center h-screen bg-gray-200">
    <form action="{{ route('admin.reset_password_submit') }}" method="post" class="w-full max-w-lg">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        
        <div class="flex bg-white rounded-2xl shadow-lg overflow-hidden w-full mt-8">
            <div class="w-1/2 p-6 text-center">
                <h2 class="text-lg font-bold mb-4">Reset Password</h2>
                
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li class="text-red-500 text-sm">{{ $error }}</li>
                    @endforeach
                @endif

                @if (Session::has('error'))
                    <li class="text-red-500 text-sm">{{ Session::get('error') }}</li>
                @endif
                @if (Session::has('success'))
                    <li class="text-green-500 text-sm">{{ Session::get('success') }}</li>
                @endif

                <div class="flex items-center border-b border-gray-400 py-2">
                    <i class="fas fa-user text-gray-500 mr-3"></i>
                    <input class="w-full outline-none py-2 px-2" type="password" name="password" placeholder="New Password" required>
                </div>
                <div class="flex items-center border-b border-gray-400 py-2 mt-4">
                    <i class="fas fa-lock text-gray-500 mr-3"></i>
                    <input class="w-full outline-none py-2 px-2" type="password" name="password_confirmation" placeholder="Confirm New Password" required>
                </div>
                <button type="submit" class="w-full bg-gray-300 text-black font-bold py-2 mt-4 rounded-full hover:bg-gray-400">Submit</button>
            </div>
            <div class="w-1/2 flex justify-center items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="Profile Image" class="w-3/4 max-w-xs rounded-2xl object-cover">
            </div>
        </div>
    </form>
    
    
</body>
</html>
