<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    
        // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "users")
        protected $table = 'users';
    
        // الحقول القابلة للتعديل
        protected $fillable = [
            'name',
            'phone',
            'password',
            'theType',
            'statu'
        ];
    
        // الحقول التي لا يمكن تعديلها
        // protected $guarded = ['id'];
    
        // لتأمين كلمة السر وتشفيرها عند إنشاء أو تحديث المستخدم
        protected static function boot()
        {
            parent::boot();
    
            static::creating(function ($user) {
                $user->password = bcrypt($user->password);
            });
    
            static::updating(function ($user) {
                $user->password = bcrypt($user->password);
            });
        }
    
    
}
