<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{

    protected $fillable = ['name', 'description', 'price', 'category_id', 'user_id', 'image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
