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
        .content {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .profile-card {
            background: rgb(251, 251, 251);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px; /* Agar ukurannya tidak terlalu besar */
            width: 100%;
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

    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="profile-card">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('storage/' . $admin->photo) }}" alt="Profile Picture" class="rounded-circle" width="150">
                            </div>
                            <div class="col-md-8">
                                <h2><?= $admin->name ?></h2>
                                <p><strong>Name:</strong> <?= $admin->name ?></p>
                                <p><strong>Email:</strong> <?= $admin->email ?></p>
                                <p><strong>Contact:</strong> <?= $admin->phone ?></p>
                                <p><strong>Address:</strong> <?= $admin->address ?></p>
                                <p><strong>Gender:</strong> <?= $admin->gender ?? 'Tidak Diketahui' ?></p>
                                <p><strong>Status:</strong> <?= $admin->status ?></p>
                                <p><strong>Bio:</strong> <?= $admin->bio ?></p>
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
