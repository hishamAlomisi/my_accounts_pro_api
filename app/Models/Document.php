<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'name',
    ];
}
