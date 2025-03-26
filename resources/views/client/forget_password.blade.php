<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #dfe3eb;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 600px;
            margin-top: 30px;
            align-items: center;
            justify-content: space-between;
        }
        .login-form {
            width: 50%;
            padding: 20px;
            text-align: center;
        }
        .login-form h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .input-group {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ccc;
            margin: 15px 0;
            padding: 5px;
        }
        .input-group i {
            margin-right: 10px;
            color: #666;
        }
        input[type="text"], input[type="password"] {
            border: none;
            outline: none;
            width: 100%;
            padding: 10px;
            font-size: 14px;
        }
        .email{
            border: none;
            outline: none;
            background: transparent;
            padding-left: 7px;
        }
        .btn {
            background-color: rgb(211, 216, 225);
            color: black;
            padding: 12px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #c0c4cc;
        }
        .divider {
            margin: 15px 0;
            font-size: 12px;
            color: #666;
        }
        .link {
            font-size: 12px;
            font-weight: bold;
            color: black;
        }
        .image-container {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .image-container img {
            width: 100%;
            height: auto;
            max-width: 150px;
            border-radius: 20px;
            object-fit: cover;
        }
        .forgot-password {
             font-size: 12px;
             color: #007bff;
             text-decoration: underline;
             cursor: pointer;
             margin-top: 10px;
         }

    </style>
</head>
<body>
    <!-- menampilkan pesan error -->

        @if ($errors->any())
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        @endif

    <!-- menampilkan sesi jika error dan jika sukses -->
        @if (Session::has('error'))
            <li>{{ Session::get('error') }}</li>
        @endif
        @if (Session::has('success'))
            <li>{{ Session::get('success') }}</li>
        @endif
        
<form action="{{ route('client.password_submit') }}" method="post">
    @csrf

    <div class="login-container">
        <div class="login-form">
            <h2>Forget Password</h2>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input class="email" type="email" name="email" placeholder="Email" required>
            </div>

            <button type="submit" class="btn">Email Password Reset Link</button>
           
        </div>
        <div class="image-container">
            <img src="{{ asset('images/logo.jpg') }}" alt="Profile Image"> <!-- Ganti dengan path yang benar -->
        </div>
    </div>
    </form>
   
</body>
</html>