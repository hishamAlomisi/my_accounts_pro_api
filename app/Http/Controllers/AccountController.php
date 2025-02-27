<?php

// app/Http/Controllers/AccountController.php
namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {       
        return response()->json(Account::with(['accountType','user','phones','ceilings'])->get());

    }

    public function store(Request $request)
    {try{
        $request->validate([
            'name' => 'required|string|max:255',
            'account_type_id' => 'required|integer',
            'ceilingAlert' => 'nullable|string',
        
        ]);
$check=Account::where('name',$request->name)->first();
if($check){
    return response()->json(['status'=>false,'message'=>'لايمكن تكرار اسم الحساب','data'=>[]], 200);

}
        $account = Account::create($request->all());
        return response()->json(['status'=>true,'message'=>'','data'=>$account], 200);
    }catch(e){
        return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
    }
        
    }

    public function show($id)
    {

        return response()->json(Account::with(['accountType','user','phones','ceilings'])-> findOrFail($id));
      
    }

    public function update(Request $request, $id)
    { 
        try{$request->validate([
        'id' => 'required|integer',
        'name' => 'required|string|max:255',
        'account_type_id' => 'required|integer',
        'ceilingAlert' => 'nullable|string',
    
    ]);
        
        $account = Account::where('id',$id)->first();
        if(!$account){
            return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
        }

        $check=Account::where('name',$request->name)->where('id','!=',$request->id)->first();
        if($check){
            return response()->json(['status'=>false,'message'=>'لايمكن تكرار اسم الحساب','data'=>[]], 200);
        
        }
        Account::findOrFail($id)->update($request->all());
            
                return response()->json(['status'=>true,'message'=>'','data'=>$account], 200);
            }catch(e){
                return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
            }
    }

    public function destroy($id)
    {
      
        try{
            $account = Account::where('id',$id)->first();
        if(!$account){
            return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
        }
            Account::findOrFail($id)->delete();
            return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
        }catch(e){
            return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
        }
    }
}

