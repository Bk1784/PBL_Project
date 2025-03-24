<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100%;
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
            background: rgb(216, 230, 243);
            border-radius: 5px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Galaxy Store</h2>
        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
        <a href="#"><i class="fa fa-wallet"></i> Pembayaran</a>
        <a href="#"><i class="fa fa-box"></i> Produk</a>
        <a href="#"><i class="fa fa-paint-brush"></i> Dekorasi</a>
        <a href="#"><i class="fa fa-cog"></i> Pengaturan</a>
        <a href="{{ route('admin.logout') }}" class="text-danger"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="container mt-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Edit Profil Admin</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('admin.update.profile') }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-4 text-center">
                            <img src="{{ asset('storage/' . $admin->photo) }}" alt="Profile Picture" class="rounded-circle" width="150">
                                <input type="file" name="photo" class="form-control">
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Contact</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $admin->phone }}">
                                </div>
                                <div class="mb-3">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ $admin->address }}">
                                </div>
                                <div class="mb-3">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="Laki-Laki" {{ $admin->gender == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="Perempuan" {{ $admin->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Status</label>
                                    <textarea name="status" class="form-control" rows="3">{{ $admin->status }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Bio</label>
                                    <textarea name="bio" class="form-control" rows="3">{{ $admin->bio }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
