<?php

// app/Http/Controllers/AccountCeilingController.php
namespace App\Http\Controllers;

use App\Models\AccountCeiling;
use Illuminate\Http\Request;

class AccountCeilingController extends Controller
{
    public function index()
    {       
        return response()->json(AccountCeiling::with(['account','currency']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'account_id' => 'required|integer',
            'currency_id' => 'required|integer',
        ]);

        $accountCeiling = AccountCeiling::create($request->all());

        return response()->json($accountCeiling, 201);
    }

    public function show($id)
    {
        return response()->json(AccountCeiling::with(['account', 'currency'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $accountCeiling = AccountCeiling::findOrFail($id);

        $accountCeiling->update($request->all());

        return response()->json($accountCeiling);
    }

    public function destroy($id)
    {
        AccountCeiling::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}

