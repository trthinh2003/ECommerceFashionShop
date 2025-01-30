<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'sex',
        'username',
        'password',
        'position',
        'role',
        'status',
    ];

    //1 NhanVien tao n PhieuNhap
    public function Inventories() {
        return $this->hasMany(Inventory::class);
    }
}
