<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendations extends Model
{
    protected $fillable = ['user_id', 'plate_id', 'score', 'label', 'status', 'warning_message'];

    public function user() {
    return $this->belongsTo(User::class);
}

}
