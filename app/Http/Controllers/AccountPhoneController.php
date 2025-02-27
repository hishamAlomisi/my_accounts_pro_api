<?php

// app/Http/Controllers/AccountPhoneController.php
namespace App\Http\Controllers;

use App\Models\AccountPhone;
use Illuminate\Http\Request;

class AccountPhoneController extends Controller
{
    public function index()
    {
        return response()->json(AccountPhone::with(['account_id'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
            'typeName' => 'required|string',
            'account_id' => 'required|integer',
        ]);

        $accountPhone = AccountPhone::create($request->all());

        return response()->json($accountPhone, 201);
    }

    public function show($id)
    {
        return response()->json(AccountPhone::with(['account_id'])->findOrFail($id));
    }
    public function update(Request $request, $id)
    {
        $accountPhone = AccountPhone::findOrFail($id);

        $accountPhone->update($request->all());

        return response()->json($accountPhone);
    }

    public function destroy($id)
    {
        AccountPhone::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}

