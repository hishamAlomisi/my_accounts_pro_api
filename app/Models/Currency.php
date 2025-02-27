<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "currencies")
    protected $table = 'currencies';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'name',
        'price',
        'mainPrice',
        'maxPrice',
        'symbol'
    ];

    // العلاقة مع جدول AccountCeiling (إذا كنت تريد استرجاع الحسابات المرتبطة بالعملة)
    public function accountCeilings()
    {
        return $this->hasMany(AccountCeiling::class, 'currency_id');
    }
}
