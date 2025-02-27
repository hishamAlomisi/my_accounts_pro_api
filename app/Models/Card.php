<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Card extends Model
{ 
    protected $table = 'cards';
    // الحقول القابلة للتعديل
    protected $fillable = [
        'name',
        'phone',
        'image1',
        'image2'
    ];
}
