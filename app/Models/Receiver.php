<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    protected $table = 'receivers';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'name',
        'phone'
       
    ];
}
