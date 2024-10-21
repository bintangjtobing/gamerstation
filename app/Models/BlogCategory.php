<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'admin_id' => 'integer',
        'category_id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'tags' => 'string',
        'details' => 'object',
        'status' => 'integer',
        'data'    => "object",
    ];
    public function getEditDataAttribute()
    {

        $data = [
            'id'      => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'status'      => $this->status,
            'data'          => $this->data
        ];

        return json_encode($data);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', false);
    }

    public function scopeSearch($query, $text)
    {
        $query->Where("name", "like", "%" . $text . "%");
    }
}
