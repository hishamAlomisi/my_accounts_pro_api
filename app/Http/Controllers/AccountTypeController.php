<?php

// app/Http/Controllers/AccountTypeController.php
namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    public function index()
    {
        return response()->json(AccountType::with(['accounts.phones','accounts.ceilings.currency'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $accountType = AccountType::create($request->all());

        return response()->json($accountType, 201);
    }

    public function show($id)
    {
        return response()->json(AccountType::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $accountType = AccountType::findOrFail($id);

        $accountType->update($request->all());

        return response()->json($accountType);
    }

    public function destroy($id)
    {
        AccountType::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}

