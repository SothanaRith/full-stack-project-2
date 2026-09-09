<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductModel extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'badge',
        'name',
        'description',
        'price',
        'currency',
        'tagline',
        'images'
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->images) {
            return null;
        }

        if (Str::startsWith($this->images, ['http://', 'https://', '//'])) {
            return $this->images;
        }

        return asset('storage/' . $this->images);
    }
}
