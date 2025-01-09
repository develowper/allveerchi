<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_fa',
        'name_en',
        'pn',
        'price',
        'image_url',
        'image_indicator',
        'in_shop',
        'in_repo',
    ];


}
