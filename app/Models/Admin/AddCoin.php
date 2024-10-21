<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddCoin extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'coin' => 'integer',
        'price' => 'integer',
    ];
    use HasFactory;
}
