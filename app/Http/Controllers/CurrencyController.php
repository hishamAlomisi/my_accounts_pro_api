<?php

// app/Http/Controllers/CurrencyController.php
namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        return response()->json(['status'=>true,'message'=>'','data'=>Currency::all()], 200);

    }

    public function store(Request $request)
    {try{
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'mainPrice' => 'required|numeric',
            'maxPrice' => 'required|numeric',
            'symbol' => 'required|string|max:10',
        ]);
$check=Currency::where('name',$request->name)->orWhere('symbol',$request->symbol)->first();
if($check){
    return response()->json(['status'=>false,'message'=>'لايمكن تكرار اسم او رمز العملة','data'=>[]], 200);

}
        $currency = Currency::create($request->all());
        return response()->json(['status'=>true,'message'=>'','data'=>$currency], 200);
    }catch(e){
        return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
    }
    
    }

    public function show($id)
    {
        return response()->json(Currency::findOrFail($id));
    }

    public function update(Request $request, $id)
    {try{
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'mainPrice' => 'required|numeric',
            'maxPrice' => 'required|numeric',
            'symbol' => 'required|string|max:10',
        ]);
        $currency = Currency::where('id',$id)->first();
        if(!$currency){
            return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
        }
        $currency = Currency::findOrFail($id);
$check=Currency::where('name',$request->name)->where('id','!=',$request->id)->orWhere('symbol',$request->symbol)->first();
if($check){
    return response()->json(['status'=>false,'message'=>'لايمكن تكرار اسم او رمز العملة','data'=>[]], 200);

}
        $currency->update($request->all());
        return response()->json(['status'=>true,'message'=>'','data'=>$currency], 200);
    }catch(e){
        return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
    }
    }

    public function destroy($id)
    {try{
        $currency = Currency::where('id',$id)->first();
        if(!$currency){
            return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
        }
        Currency::findOrFail($id)->delete();
        return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
    }catch(e){
        return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
    }
    }
}

