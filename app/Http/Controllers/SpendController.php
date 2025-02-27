<?php

namespace App\Http\Controllers;
use App\Models\Currency;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Spend;
use App\Http\Controllers\EntryController;

class SpendController extends Controller
{
    public function index()
    {
        return response()->json(Spend::with(['entry.user', 'currency', 'fromAccount', 'toAccount'])->get());

    
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $currency=Currency::where('id',$request->currency_id)->first();
           $entry= DB::table('entries')->insertGetId([
                'document_id' => $request->document_id,
                'docNumber' => 0,
                'date' => $request->date,
                'note' =>$request->note??'',
                'isDepend' => 1,
                'user_id' => $request->user_id,
            ]);
            $document= DB::table('spends')->insertGetId ([
                'entry_id' => $entry,
                'amount' => $request->amount,
                'receiver' => $request->receiver,
                'currency_id' => $request->currency_id,
                'from_account_id' =>$request->from_account_id,
                'to_account_id' => $request->to_account_id,
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
                'account_id' => $request->from_account_id,
                'note' => $request->maden_note??'',
            ]);
            DB::table('entry_details')->insertGetId([
                'entry_id' => $entry,
                'amount' => $request->amount,
                'currency_id' => $request->currency_id,
                'mcAmount' => $request->amount*$currency->price,
                'account_id' => $request->to_account_id,
                'note' => $request->daen_note??'',
            ]);
          
            if(!empty($request->receiver??'')){
                DB::table('receivers')->updateOrInsert(
                    ['name' => $request->receiver ?? ''], // الشرط الذي تبحث من خلاله
                    ['name' => $request->receiver ?? '','phone'=>'']  // القيم التي ستضاف عند عدم وجود السجل
                );
            }
            if(!empty($request->note??'')){
                DB::table('notes')->updateOrInsert(
                    ['note' => $request->note ?? ''], // الشرط الذي تبحث من خلاله
                    ['note' => $request->note ?? '']  // القيم التي ستضاف عند عدم وجود السجل
                );
            }
            $entryController = new EntryController();
            $response = $entryController->statistics();
            $results=Spend::with(['entry.user', 'currency', 'fromAccount', 'toAccount'])->where('entry_id',$entry)->first();
            DB::commit();
            return response()->json(['status'=>true,'message'=>'','data'=>$results,'statistics'=>$response], 200);
        }catch(e){
            DB::rollBack();
            return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
        }
    }

    public function show($id)
    {
        $spend = Spend::with(['entry.user', 'currency', 'fromAccount', 'toAccount'])->findOrFail($id);
        return response()->json($spend);
    }

    public function update(Request $request, $id)
    {
        $currency=Currency::where('id',$request->currency_id)->first();
        $constraint = Spend::findOrFail($id);
        try{
            $request->validate([
                 'amount' => 'required|numeric',
                'currency_id' => 'required|integer',
                'from_account_id' => 'required|integer',
                'to_account_id' => 'required|integer',
            ]);
          
            DB::beginTransaction();
            DB::table('entries')->where('id',$constraint->entry_id)->update([
                'date' => $request->date,
                'note' =>$request->note??'',
                'user_id' => $request->user_id,
            ]);
            DB::table('spends')->where('id',$constraint->id)->update([
                'amount' => $request->amount,
                'receiver' => $request->receiver,
                'currency_id' => $request->currency_id,
                'from_account_id' =>$request->from_account_id,
                'to_account_id' => $request->to_account_id,
                'note' => $request->note??'',
            ]);
           
            DB::table('entry_details')->where('entry_id',$constraint->entry_id)->where('amount','<',0)->update([
                'amount' => -1*$request->amount,
                'currency_id' => $request->currency_id,
                'mcAmount' => -1*$request->amount*$currency->price,
                'account_id' => $request->from_account_id,
                'note' => $request->maden_note??'',
            ]);
            DB::table('entry_details')->where('entry_id',$constraint->entry_id)->where('amount','>',0)->update([
                'amount' => $request->amount,
                'currency_id' => $request->currency_id,
                'mcAmount' => $request->amount*$currency->price,
                'account_id' => $request->to_account_id,
                'note' => $request->daen_note??'',
            ]);
    
            $entryController = new EntryController();
            $response = $entryController->statistics();
            $results=Spend::with(['entry.user', 'currency', 'fromAccount', 'toAccount'])->where('entry_id',$constraint->entry_id)->first();

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
            $constraint = Spend::where('id',$id)->first();
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

