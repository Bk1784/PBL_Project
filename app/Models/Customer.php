<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $guard = 'customer';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'kontak',
        'alamat',
        'jenis_kelamin',
        'bio',
        'photo',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}