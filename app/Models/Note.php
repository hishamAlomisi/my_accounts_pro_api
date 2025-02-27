<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'note',
    ];
}
