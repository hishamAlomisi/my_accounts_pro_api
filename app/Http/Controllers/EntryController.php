<?php

// app/Http/Controllers/EntryController.php
namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class EntryController extends Controller
{
    public function index()
    {
        return response()->json(Entry::with(['document', 'user'])->get());
    }
    public function statistics(){
        $result1 = DB::table('entry_details as e')
    ->join('currencies as c', 'e.currency_id', '=', 'c.id')  // الربط بين الجداول
    ->where('c.price', '>', 1.0)  // الشرط على السعر
    ->selectRaw('sum(e.amount) as amount, e.currency_id as currency')  // حساب المجموع
    ->groupBy('e.currency_id')  // تجميع حسب العملة
    ->get();

        $results = DB::table('entries')
        ->selectRaw('
            sum(case when document_id = 1 then 1 else 0 end) as simpleTies,
            sum(case when document_id = 2 then 1 else 0 end) as receives,
            sum(case when document_id = 3 then 1 else 0 end) as spends,
            sum(case when document_id = 4 then 1 else 0 end) as buyCurrencies,
            sum(case when document_id = 5 then 1 else 0 end) as sellCurrencies,
            sum(case when document_id = 6 then 1 else 0 end) as sendTransfers,
            sum(case when document_id = 7 then 1 else 0 end) as receiveTransfers
        ')
        ->first();
    return response()->json(["currenciesAmounts"=> $result1,"all"=>$results]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|integer',
            'date' => 'required|date',
            'user_id' => 'required|integer',
        ]);

        $entry = Entry::create($request->all());

        return response()->json($entry, 201);
    }

    public function show($id)
    {
        return response()->json(Entry::with(['document_id', 'user_id'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $entry = Entry::findOrFail($id);

        $entry->update($request->all());

        return response()->json($entry);
    }

    public function destroy($id)
    {
        Entry::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
