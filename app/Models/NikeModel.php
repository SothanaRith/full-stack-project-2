<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NikeModel extends Model
{
    protected $table = 'nike';
    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'tagline',
        'image'
    ];
}
