<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{

    protected $fillable = ['name', 'description', 'price', 'category_id', 'user_id', 'image', 'is_available'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'plate_ingredients', 'plate_id', 'ingredient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function recommendations()
    {
        return $this->hasMany(Recommendations::class);
    }
}
