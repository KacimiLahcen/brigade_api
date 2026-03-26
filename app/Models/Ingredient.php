<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'tags'];


    protected $casts = [
        'tags' => 'array', // to compare later with dietary_tags 
    ];

    public function plates()
    {
        return $this->belongsToMany(Plat::class, 'plate_ingredients', 'ingredient_id', 'plate_id');
    }
}
