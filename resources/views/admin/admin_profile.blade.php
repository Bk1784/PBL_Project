<?php
use Illuminate\Support\Facades\Auth;

$admin = Auth::guard('admin')->user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100px;
            background: white;
            position: fixed;
            padding: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #000;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background:rgb(216, 230, 243);
            border-radius: 5px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .profile-card {
            background: rgb(251, 251, 251);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .edit-btn {
            background-color: #adb5bd;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.5s;
        }
        .edit-btn:hover {
            background-color: #6c757d;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Galaxy Store</h2>
        <a href="{{ route('admin.dashboard') }}" ><i class="fa fa-home"></i> Dashboard</a>
        <a href="#"><i class="fa fa-wallet"></i> Pembayaran</a>
        <a href="#"><i class="fa fa-box"></i> Produk</a>
        <a href="#"><i class="fa fa-paint-brush"></i> Dekorasi</a>
        <a href="#"><i class="fa fa-cog"></i> Pengaturan</a>
        <a href="{{ route('admin.logout') }}" class="text-danger"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="container">
            <div class="profile-card">
                <div class="row">
                    <div class="col-md-3 text-center">
                    <img src="{{ asset('storage/' . $admin->photo) }}" alt="Profile Picture" class="rounded-circle" width="150">
                    </div>
                    <div class="col-md-9">
                        <h2><?= $admin->name ?></h2>
                        <p><strong>Name:</strong><?= $admin->name ?></p>
                        <p><strong>Email:</strong><?= $admin->email ?></p>
                        <p><strong>Contact:</strong><?= $admin->phone ?></p>
                        <p><strong>Address:</strong><?= $admin->address ?></p>
                        <p><strong>Gender:</strong><?= $admin->gender ?? 'Tidak Diketahui' ?></p>
                        <p><strong>Status:</strong><?= $admin->status ?></p>
                        <p><strong>Bio:</strong><?= $admin->bio?></p>
                        <a href="{{ route('admin.edit.profile') }}" class="btn edit-btn">EDIT</a>
                    </div>
                </div>
            </div>
            <div class="profile-card mt-3 text-end">
                <h3>Tanggung Jawab</h3>
                <p>Menerima pesanan, mengeksekusi pesanan, mengedit produk</p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <h5>Alamat</h5>
                    <p class="text-break">Srono, Banyuwangi, Jawa Timur, Indonesia</p>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Ikuti Kami</h5>
                    <p>
                        <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a> |
                        <a href="https://shopee.co.id" target="_blank">Shopee</a>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Kontak Kami</h5>
                    <p>0812345678908</p>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="https://facebook.com" target="_blank" class="me-3"><i class="fab fa-facebook fa-2x"></i></a>
                <a href="https://instagram.com" target="_blank" class="me-3"><i class="fab fa-instagram fa-2x"></i></a>
                <a href="https://youtube.com" target="_blank"><i class="fab fa-youtube fa-2x"></i></a>
            </div>
            <p class="mt-3">&copy; 2025 Galaxy Store. Project Based Learning</p>
        </div>
    </footer>
</body>
</html>