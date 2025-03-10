<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table = 'account_types';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'name',
    ];
    public function accounts()
    {
        return $this->hasMany(Account::class, 'account_type_id');
    }
}
