<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BikeModel extends Model
{
    protected $table = 'bike';

    protected $fillable = [
        'id',
        'brand',
        'image',
        'model',
        'year',
        'color',
        'price'
    ];
}
