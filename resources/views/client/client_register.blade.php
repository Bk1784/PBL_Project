<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Register Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #dfe3eb;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding-top: 50px;
        }
        .login-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 600px;
            align-items: center;
            justify-content: space-between;
            margin: auto;
        }
        .login-form {
            width: 50%;
            padding: 20px;
            text-align: center;
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
        input[type="text"], input[type="password"], input[type="email"] {
            border: none;
            outline: none;
            width: 100%;
            padding: 10px;
            font-size: 14px;
            background: transparent;
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
        .image-container {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .image-container img {
            width: 100%;
            max-width: 150px;
            border-radius: 20px;
            object-fit: cover;
        }
        .footer-container {
            width: 100%;
            background-color: #e5eaf2;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="{{ route('client.register.submit') }}" method="post">
        @csrf
        <div class="login-container">
            <div class="login-form">
                <h2>Client Register</h2>
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                @if (Session::has('error'))
                    <li>{{ Session::get('error') }}</li>
                @endif
                @if (Session::has('success'))
                    <li>{{ Session::get('success') }}</li>
                @endif
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="Nama Toko" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone" placeholder="Nomor HP" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="address" placeholder="Alamat" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Submit</button>
                <p class="divider">ATAU</p>
                <p class="link"><a href="#">DAFTAR</a> tidak memiliki akun</p>
                <a href="{{ route('admin.forget_password') }}">Forget Password?</a>
            </div>
            <div class="image-container">
                <img src="{{ asset('images/logo.jpg') }}" alt="Profile Image">
            </div>
        </div>
    </form>
    <div class="footer-container">
        <p>© 2025 Galaxy Store - All Rights Reserved.</p>
    </div>
</body>
</html>
