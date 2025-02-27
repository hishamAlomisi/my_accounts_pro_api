<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Currency;
use App\Models\Entry;
use App\Models\BuyCurrency;
use Illuminate\Http\Request;
use App\Http\Controllers\EntryController;
class BuyCurrencyController extends Controller
{
    public function index()
    {
        return response()->json(BuyCurrency::with(['entry.user', 'currency', 'account', 'mcCurrency'])->get());
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $currency=Currency::where('id',$request->currency_id)->first();
            $mc_currency=Currency::where('id',$request->mc_currency_id)->first();
           $entry= DB::table('entries')->insertGetId([
                'document_id' => $request->document_id,
                'docNumber' => 0,
                'date' => $request->date,
                'note' =>$request->note??'',
                'isDepend' => 1,
                'user_id' => $request->user_id,
            ]);
           
            $document= DB::table('buy_currencies')->insertGetId ([
                'entry_id' => $entry,
                'amount' => $request->amount,
                'price' => $request->price,
                'mcAmount' => $request->mcAmount,
                'currency_id' => $request->currency_id,
                'mc_currency_id' =>$request->mc_currency_id,
                'account_id' => $request->account_id,
                'note' => $request->note??'',
            ]);
            DB::table('entries')->where('id',$entry)->update([
                'docNumber' => $document,
            ]);
            DB::table('entry_details')->insertGetId([
                'entry_id' => $entry,
                'amount' => -1*$request->amount,
                'currency_id' => $request->currency_id,
                'mcAmount' => -1*$request->amount*$currency->price,
                'account_id' => $request->account_id,
                'note' => $request->maden_note??'',
            ]);
            DB::table('entry_details')->insertGetId([
                'entry_id' => $entry,
                'amount' => $request->mcAmount,
                'currency_id' => $request->mc_currency_id,
                'mcAmount' => $request->mcAmount*$mc_currency->price,
                'account_id' => $request->account_id,
                'note' => $request->daen_note??'',
            ]);
         
        

            $different=($request->amount*$currency->price)-($request->mcAmount*$mc_currency->price);
if($different!=0){
    DB::table('entry_details')->insertGetId([
        'entry_id' => $entry,
        'amount' => $different/$mc_currency->price,
        'currency_id' => $request->mc_currency_id,
        'mcAmount' => $different,
        'account_id' => 1,
        'note' => $request->deff_note??'',
    ]);

}
    
$entryController = new EntryController();
$response = $entryController->statistics();
            $results=BuyCurrency::with(['entry.user', 'currency', 'account', 'mcCurrency'])->where('entry_id',$entry)->first();
            DB::commit();
            return response()->json(['status'=>true,'message'=>'','data'=>$results,'statistics'=>$response], 200);
        }catch(e){
            DB::rollBack();
            return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
        }
    }

    public function show($id)
    {
        return response()->json(BuyCurrency::with(['entry', 'currency', 'account', 'mcCurrency'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $constraint = BuyCurrency::findOrFail($id);
            $currency=Currency::where('id',$request->currency_id)->first();
            $mc_currency=Currency::where('id',$request->mc_currency_id)->first();
            DB::table('entries')->where('id',$constraint->entry_id)->update([
                'date' => $request->date,
                'note' =>$request->note??'',
                'user_id' => $request->user_id,
            ]);
         
                DB::table('buy_currencies')->where('id',$constraint->id)->update([
                'amount' => $request->amount,
                'price' => $request->price,
                'mcAmount' => $request->mcAmount,
                'currency_id' => $request->currency_id,
                'mc_currency_id' =>$request->mc_currency_id,
                'account_id' => $request->account_id,
                'note' => $request->note??'',
            ]);
            DB::table('entry_details')->where('entry_id',$constraint->entry_id)->where('account_id',1)->delete();

            DB::table('entry_details')->where('entry_id',$constraint->entry_id)->where('amount','<',0)->update([
                'amount' => -1*$request->amount,
                'currency_id' => $request->currency_id,
                'mcAmount' => -1*$request->amount*$currency->price,
                'account_id' => $request->account_id,
                'note' => $request->maden_note??'',
            ]);
            DB::table('entry_details')->where('entry_id',$constraint->entry_id)->where('amount','>',0)->update([
                'amount' => $request->mcAmount,
                'currency_id' => $request->mc_currency_id,
                'mcAmount' => $request->mcAmount*$mc_currency->price,
                'account_id' => $request->account_id,
                'note' => $request->daen_note??'',
            ]);
         
        

            $different=($request->amount*$currency->price)-($request->mcAmount*$mc_currency->price);
if($different!=0){
    DB::table('entry_details')->insertGetId([
        'entry_id' => $constraint->entry_id,
        'amount' => $different/$mc_currency->price,
        'currency_id' => $request->mc_currency_id,
        'mcAmount' => $different,
        'account_id' => 1,
        'note' => $request->deff_note??'',
    ]);

}
    
$entryController = new EntryController();
$response = $entryController->statistics();
            $results=BuyCurrency::with(['entry.user', 'currency', 'account', 'mcCurrency'])->where('entry_id',$constraint->entry_id)->first();
            DB::commit();
            return response()->json(['status'=>true,'message'=>'','data'=>$results,'statistics'=>$response], 200);
        }catch(e){
            DB::rollBack();
            return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
        }
    }

    public function destroy($id)
    {
        try{
            $constraint = BuyCurrency::where('id',$id)->first();
            if(!$constraint){
                return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
            }
            Entry::findOrFail($constraint->entry_id)->delete();
            return response()->json(['status'=>true,'message'=>'','data'=>''], 200);
        }catch(e){
            return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
        }
    }
}

