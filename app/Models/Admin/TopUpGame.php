<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopUpGame extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'google_sotre' => 'string',
        'apple_sotre' => 'string',
        'input_fields' => 'object',

    ];

    public function scopeSearch($query, $data)
    {
        // $data = Str::slug($data);
        return $query->where("title", "like", "%" . $data . "%")
            ->orWhere('description', 'like', '%' . $data . '%');
    }
}
