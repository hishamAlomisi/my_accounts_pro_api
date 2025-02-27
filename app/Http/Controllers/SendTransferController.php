<?php

// app/Http/Controllers/SendTransferController.php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Currency;
use App\Models\Entry;
use App\Models\Note;
use App\Models\Receiver;
use App\Models\SendTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\EntryController;
class SendTransferController extends Controller
{
    public function index()
    {
        return response()->json(SendTransfer::with(['entry.user', 'currency', 'account', 'agentAccount','agentCommCurrency','transferCommCurrency'])->get());
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
            $document= DB::table('send_transfers')->insertGetId ([
                'entry_id' => $entry,
                'amount' => $request->amount,
                'currency_id' => $request->currency_id,
                'transferComm' => $request->transferComm,
                'transfer_comm_currency_id' => $request->transfer_comm_currency_id,
                'receiverName' =>$request->receiverName,
                'receiverPhone' => $request->receiverPhone??"",
                'senderName' =>$request->senderName,
                'senderPhone' => $request->senderPhone??"",
                'note' => $request->note??"",
                'account_id' =>$request->account_id,
                'agent_account_id' => $request->agent_account_id,
                'agentComm' => $request->agentComm,
                'agent_comm_currency_id' => $request->agent_comm_currency_id,
                'transferNumber' => $request->transferNumber??"",
                'status' => $request->status??1,
                'target' => $request->target??"",
            ]);
            DB::table('entries')->where('id',$entry)->update([
                'docNumber' => $document,
            ]);
            DB::table('entry_details')->insertGetId([
                'entry_id' => $entry,
                'amount' => -1*$request->madenAmount,
                'currency_id' => $request->currency_id,
                'mcAmount' => -1*$request->madenAmount*$currency->price,
                'account_id' => $request->account_id,
                'note' => $request->maden_note??'',
            ]);
            DB::table('entry_details')->insertGetId([
                'entry_id' => $entry,
                'amount' => $request->agentAmount,
                'currency_id' => $request->currency_id,
                'mcAmount' => $request->agentAmount*$currency->price,
                'account_id' => $request->agent_account_id,
                'note' => $request->daen_note??'',
            ]);
           
         if($request->transferComm>0){
            $transferCommCurrency=Currency::where('id',$request->transfer_comm_currency_id)->first();
            if($transferCommCurrency->id!=$currency->id){

                DB::table('entry_details')->insertGetId([
                    'entry_id' => $entry,
                    'amount' => -1*$request->transferComm,
                    'currency_id' => $transferCommCurrency->id,
                    'mcAmount' => -1*$request->transferComm*$transferCommCurrency->price,
                    'account_id' => $request->account_id,
                    'note' => $request->comm_note??'',
                ]);
            }
            DB::table('entry_details')->insertGetId([
                'entry_id' => $entry,
                'amount' => $request->transferComm,
                'currency_id' => $transferCommCurrency->id,
                'mcAmount' => $request->transferComm*$transferCommCurrency->price,
                'account_id' => 2,
                'note' => $request->comm_note??'',
            ]);

         }
         if($request->agentComm>0){
            $agentCommCurrency=Currency::where('id',$request->agent_comm_currency_id)->first();
            if($agentCommCurrency->id!=$currency->id){
                DB::table('entry_details')->insertGetId([
                    'entry_id' => $entry,
                    'amount' => $request->agentComm,
                    'currency_id' => $agentCommCurrency->id,
                    'mcAmount' => $request->agentComm*$agentCommCurrency->price,
                    'account_id' => $request->agent_account_id,
                    'note' => $request->comm_note??'',
                ]);
               
            }
            DB::table('entry_details')->insertGetId([
                'entry_id' => $entry,
                'amount' => -1*$request->agentComm,
                'currency_id' => $agentCommCurrency->id,
                'mcAmount' => -1*$request->agentComm*$agentCommCurrency->price,
                'account_id' =>3,
                'note' => $request->comm_note??'',
            ]);

         }
  
         if(!empty($request->receiverName??'')){
            DB::table('receivers')->updateOrInsert(
                ['name' => $request->receiverName?? ''], // الشرط الذي تبحث من خلاله
                ['name' => $request->receiverName ?? '','phone'=>$request->receiverPhone??""]  // القيم التي ستضاف عند عدم وجود السجل
            );
        }
        if(!empty($request->senderName??'')){
            DB::table('receivers')->updateOrInsert(
                ['name' => $request->senderName?? ''], // الشرط الذي تبحث من خلاله
                ['name' => $request->senderName ?? '','phone'=>$request->senderPhone??""]  // القيم التي ستضاف عند عدم وجود السجل
            );
        }
        if(!empty($request->note??'')){
            DB::table('notes')->updateOrInsert(
                ['note' => $request->note ?? ''], // الشرط الذي تبحث من خلاله
                ['note' => $request->note ?? '']  // القيم التي ستضاف عند عدم وجود السجل
            );
        }
        $receivers=Receiver::all();
        $notes=Note::all();
        $targets = DB::table('send_transfers')
        ->distinct()
        ->pluck('target');
        $entryController = new EntryController();
        $response = $entryController->statistics();
            $results=SendTransfer::with(['entry.user', 'currency', 'account', 'agentAccount','agentCommCurrency','transferCommCurrency'])->where('entry_id',$entry)->first();
            DB::commit();
            return response()->json(['status'=>true,'message'=>'','data'=>$results,'receivers'=>$receivers,'notes'=>$notes,'targets'=>$targets,'statistics'=>$response], 200);
        }catch(e){
            DB::rollBack();
            return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
        }
    }

    public function show($id)
    {
        return response()->json(SendTransfer::with(['entry.user', 'currency', 'account', 'agentAccount','agentCommCurrency','transferCommCurrency'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $currency=Currency::where('id',$request->currency_id)->first();
            $constraint = SendTransfer::findOrFail($id);
            DB::table('entries')->where('id',$constraint->entry_id)->update([
                'date' => $request->date,
                'note' =>$request->note??'',
                'user_id' => $request->user_id,
            ]);
            DB::table('send_transfers')->where('id',$constraint->id)->update([
                'amount' => $request->amount,
                'currency_id' => $request->currency_id,
                'transferComm' => $request->transferComm,
                'transfer_comm_currency_id' => $request->transfer_comm_currency_id,
                'receiverName' =>$request->receiverName,
                'receiverPhone' => $request->receiverPhone??"",
                'senderName' =>$request->senderName,
                'senderPhone' => $request->senderPhone??"",
                'note' => $request->note??"",
                'account_id' =>$request->account_id,
                'agent_account_id' => $request->agent_account_id,
                'agentComm' => $request->agentComm,
                'agent_comm_currency_id' => $request->agent_comm_currency_id,
                'transferNumber' => $request->transferNumber??"",
                'target' => $request->target??"",
            ]);
            DB::table('entry_details')->where('entry_id',$constraint->entry_id)->whereIn('account_id', [2, 3])->delete();
            DB::table('entry_details')->where('entry_id',$constraint->entry_id)->where('note', 'like', '%مقابل عمولة%')->delete();
            DB::table('entry_details')->where('id',$constraint->id)->where('amount','<',0)->update([
                'entry_id' => $constraint->entry_id,
                'amount' => -1*$request->madenAmount,
                'currency_id' => $request->currency_id,
                'mcAmount' => -1*$request->madenAmount*$currency->price,
                'account_id' => $request->account_id,
                'note' => $request->maden_note??'',
            ]);
            DB::table('entry_details')->where('id',$constraint->id)->where('amount','>',0)->update([
                'entry_id' => $constraint->entry_id,
                'amount' => $request->agentAmount,
                'currency_id' => $request->currency_id,
                'mcAmount' => $request->agentAmount*$currency->price,
                'account_id' => $request->agent_account_id,
                'note' => $request->daen_note??'',
            ]);
           
         if($request->transferComm>0){
            $transferCommCurrency=Currency::where('id',$request->transfer_comm_currency_id)->first();
            if($transferCommCurrency->id!=$currency->id){

                DB::table('entry_details')->insertGetId([
                    'entry_id' => $constraint->entry_id,
                    'amount' => -1*$request->transferComm,
                    'currency_id' => $transferCommCurrency->id,
                    'mcAmount' => -1*$request->transferComm*$transferCommCurrency->price,
                    'account_id' => $request->account_id,
                    'note' => $request->comm_note??'',
                ]);
            }
            DB::table('entry_details')->insertGetId([
                'entry_id' => $constraint->entry_id,
                'amount' => $request->transferComm,
                'currency_id' => $transferCommCurrency->id,
                'mcAmount' => $request->transferComm*$transferCommCurrency->price,
                'account_id' => 2,
                'note' => $request->comm_note??'',
            ]);

         }
         if($request->agentComm>0){
            $agentCommCurrency=Currency::where('id',$request->agent_comm_currency_id)->first();
            if($agentCommCurrency->id!=$currency->id){
                DB::table('entry_details')->insertGetId([
                    'entry_id' => $constraint->entry_id,
                    'amount' => $request->agentComm,
                    'currency_id' => $agentCommCurrency->id,
                    'mcAmount' => $request->agentComm*$agentCommCurrency->price,
                    'account_id' => $request->agent_account_id,
                    'note' => $request->comm_note??'',
                ]);
               
            }
            DB::table('entry_details')->insertGetId([
                'entry_id' => $constraint->entry_id,
                'amount' => -1*$request->agentComm,
                'currency_id' => $agentCommCurrency->id,
                'mcAmount' => -1*$request->agentComm*$agentCommCurrency->price,
                'account_id' =>3,
                'note' => $request->comm_note??'',
            ]);

         }
    
         if(!empty($request->receiverName??'')){
            DB::table('receivers')->updateOrInsert(
                ['name' => $request->receiverName?? ''], // الشرط الذي تبحث من خلاله
                ['name' => $request->receiverName ?? '','phone'=>$request->receiverPhone??""]  // القيم التي ستضاف عند عدم وجود السجل
            );
        }
        if(!empty($request->senderName??'')){
            DB::table('receivers')->updateOrInsert(
                ['name' => $request->senderName?? ''], // الشرط الذي تبحث من خلاله
                ['name' => $request->senderName ?? '','phone'=>$request->senderPhone??""]  // القيم التي ستضاف عند عدم وجود السجل
            );
        }
        if(!empty($request->note??'')){
            DB::table('notes')->updateOrInsert(
                ['note' => $request->note ?? ''], // الشرط الذي تبحث من خلاله
                ['note' => $request->note ?? '']  // القيم التي ستضاف عند عدم وجود السجل
            );
        }
        $receivers=Receiver::all();
        $notes=Note::all();
        $targets = DB::table('send_transfers')
        ->distinct()
        ->pluck('target');
        $entryController = new EntryController();
        $response = $entryController->statistics();
            $results=SendTransfer::with(['entry.user', 'currency', 'account', 'agentAccount','agentCommCurrency','transferCommCurrency'])->where('entry_id',$constraint->entry_id)->first();
            DB::commit();
            return response()->json(['status'=>true,'message'=>'','data'=>$results,'receivers'=>$receivers,'notes'=>$notes,'targets'=>$targets,'statistics'=>$response], 200);
        }catch(e){
            DB::rollBack();
            return response()->json(['status'=>false,'message' => $e,'data'=>['']], 401);
        }
    }

    public function destroy($id)
    {
        try{
            $constraint = SendTransfer::where('id',$id)->first();
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
