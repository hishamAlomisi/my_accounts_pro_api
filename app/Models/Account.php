<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "accounts")
    protected $table = 'accounts';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'name',
        'account_type_id',
        'address',
        'note',
        'ceilingAlert',
        'role',
        'user_id'
    ];
    public function phones()
    {
        return $this->hasMany(AccountPhone::class, 'account_id');
    }
    public function ceilings()
    {
        return $this->hasMany(AccountCeiling::class, 'account_id');
    }
    // العلاقة مع جدول AccountType (نوع الحساب)
    public function accountType()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    // العلاقة مع جدول User (المستخدم)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
