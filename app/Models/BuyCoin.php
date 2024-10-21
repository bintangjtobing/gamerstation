<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyCoin extends Model
{
    protected $appends = ['status'];

    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'user_id'          => 'integer',
        'trx_id'    => 'string',
        'login_vender'  => 'string',
        'username_or_email' => 'string',
        'password' => 'string',
        'coin' => 'string',
        'price' => 'string',
        'charge' => 'string',
        'total_amount' => 'string',
        'status' => 'integer',
        'reject_reason' => 'string',
        'data'          => 'object',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getStringStatusAttribute()
    {
        $status = $this->status;
        $data = [
            'class' => "",
            'value' => "",
        ];
        if ($status == 2) {
            $data = [
                'class'     => "badge badge--success",
                'value'     => "Success",
            ];
        } else if ($status == 1) {
            $data = [
                'class'     => "badge badge--warning",
                'value'     => "Pending",
            ];
        } else if ($status == 4) {
            $data = [
                'class'     => "badge badge--warning",
                'value'     => "Hold",
            ];
        } else if ($status == 3) {
            $data = [
                'class'     => "badge badge--danger",
                'value'     => "Rejected",
            ];
        }

        return (object) $data;
    }
}
