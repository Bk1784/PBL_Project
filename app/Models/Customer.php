<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'username',
        'email',
        'kontak',
        'alamat',
        'jenis_kelamin',
        'bio',
        'photo',
    ];
}