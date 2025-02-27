<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\AccountType;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // عرض جميع المستخدمين
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // عرض مستخدم معين
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }
    public function login(Request $request)
{
    // التحقق من صحة البيانات المدخلة
    $request->validate([
        'phone' => 'required|string',
        'password' => 'required|string',
    ]);
    
    // طباعة البيانات المدخلة للتحقق من نوعها
 // اضافة لوج للتأكد من البيانات المدخلة


 $user = User::where('phone', $request->phone)->where('password',$request->password)->first();
    // محاولة التحقق من البيانات (اسم المستخدم وكلمة المرور)
    if ($user) {      // الحصول على المستخدم المتصل
        Auth::login($user);
        $user= Auth::user();
        $token = $user->createToken('myAccountsPro')->plainTextToken;
         $token=$user->createToken('token-name', ['server:update'])->plainTextToken;


        // إعادة التوكين في الاستجابة
        return response()->json(['status'=>true,'message'=>'','data'=>['user'=>$user,'token' => $token,"currencies"=>Currency::all(),"accounts"=>AccountType::with(['accounts.phones','accounts.ceilings.currency'])->get()]], 200);
    }

    // في حالة فشل التحقق
    return response()->json(['status'=>false,'message' => 'Unauthorized','data'=>['']], 401);
}
    // إضافة مستخدم جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'theType' => 'required|string',
            'statu' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'theType' => $request->theType,
            'statu' => $request->statu,
        ]);

        return response()->json($user, 201);
    }

    // تحديث بيانات مستخدم
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'string',
            'phone' => 'string|unique:users,phone,' . $id,
            'password' => 'string|min:6',
            'theType' => 'string',
            'statu' => 'string',
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'theType' => $request->theType ?? $user->theType,
            'statu' => $request->statu ?? $user->statu,
        ]);

        return response()->json($user);
    }
   
    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
