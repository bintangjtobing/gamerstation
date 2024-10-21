<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSetting extends Model
{
    use HasFactory;


    protected $guarded = ['id', 'slug'];


    protected $with = ['admin'];


    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    protected $casts = [
        'admin_id'       => 'integer',
        'slug'           => 'string',
        'title'          => 'string',
        'fixed_charge'   => 'decimal:8',
        'percent_charge' => 'decimal:8',
        'min_limit'      => 'decimal:8',
        'max_limit'      => 'decimal:8',
        'monthly_limit'  => 'decimal:8',
        'daily_limit'    => 'decimal:8',
        'status'         => 'integer',
    ];
}
