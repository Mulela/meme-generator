<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meme extends Model
{
    protected $fillable = ['image_path', 'top_text', 'bottom_text'];

}
