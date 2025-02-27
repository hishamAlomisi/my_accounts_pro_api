<?php

// app/Http/Controllers/EntryDetailController.php
namespace App\Http\Controllers;

use App\Models\EntryDetail;
use Illuminate\Http\Request;

class EntryDetailController extends Controller
{
    public function index()
    {
        return response()->json(EntryDetail::with(['entry_id', 'currency_id', 'account_id'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|integer',
            'amount' => 'required|numeric',
            'currency_id' => 'required|integer',
            'mcAmount' => 'required|numeric',
            'account_id' => 'required|integer',
        ]);

        $entryDetail = EntryDetail::create($request->all());

        return response()->json($entryDetail, 201);
    }

    public function show($id)
    {
        return response()->json(EntryDetail::with(['entry_id', 'currency_id', 'account_id'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $entryDetail = EntryDetail::findOrFail($id);

        $entryDetail->update($request->all());

        return response()->json($entryDetail);
    }

    public function destroy($id)
    {
        EntryDetail::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
