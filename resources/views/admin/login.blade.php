<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        .footer-container {
             width: 100%;
             background-color: #e5eaf2;
             padding: 20px;
             border-radius: 10px;
             box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .footer {
            display: flex;
             justify-content: space-around;
             align-items: center;
        }
        .footer div {
             text-align: center;
         }
         .footer h3 {
            font-size: 16px;
            font-weight: bold;
            display: flex;
            justify-content: center;
        }
        .footer i{
            margin-right: 8px;
            color: #d84315;
        }
        .footer a {
             text-decoration: none;
             color: #007bff;
             font-weight: bold;
         }
         .footer a:hover {
             color: #ff6600;
         }
         .footer p {
             font-size: 14px;
             color: #333;
             margin: 5px 0;
         }
         .footer-bottom {
             text-align: center;
             font-size: 12px;
             color: #666;
             margin-top: 10px;
         }

    </style>
</head>
<body>
    <form action="{{ route('admin.login_submit') }}" method="post">
        @csrf
        <div class="login-container">
            <div class="login-form">
                <h2>LOGIN</h2>
    <!-- menampilkan pesan error -->

        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

    <!-- menampilkan sesi jika error dan jika sukses -->
        @if (Session::has('error'))
            <li>{{ Session::get('error') }}</li>
        @endif
        @if (Session::has('success'))
            <li>{{ Session::get('success') }}</li>
        @endif
        

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input class="email" type="email" name="email" placeholder="Email" required>
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
            <img src="{{ asset('images/logo.jpg') }}" alt="Profile Image"> <!-- Ganti dengan path yang benar -->
        </div>
    </div>
    </form>
    <div class="footer-container">
         <div class="footer">
             <div>
                 <h3><i class="fas fa-map-marker-alt"></i> Alamat</h3>
                 <p>Galaxy Store, Srono</p>
             </div>
             <div>
                 <h3><i class="fas fa-bullhorn"></i> Ikuti Kami</h3>
                 <p><a href="#">Instagram</a> <a href="#" style="color: orange;">Shopee</a></p>
             </div>
             <div>
                 <h3><i class="fas fa-phone-alt"></i> Kontak Kami</h3>
                 <p>0812-3456-7890</p>
                 <p>0812-9876-5432</p>
             </div>
         </div>
         <div class="footer-bottom">
             © 2025 Galaxy Store - All Rights Reserved.
         </div>
    </div>
</body>
</html>