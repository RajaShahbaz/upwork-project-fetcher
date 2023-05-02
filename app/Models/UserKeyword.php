<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKeyword extends Model
{
    use HasFactory;
    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}
