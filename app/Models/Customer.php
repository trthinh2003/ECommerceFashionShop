<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'username',
        'password',
        'sex',
        'image',
        'platform_id', //Đăng nhập bằng nền tảng gì đó (google, facebook,...)
        'platform_name'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
