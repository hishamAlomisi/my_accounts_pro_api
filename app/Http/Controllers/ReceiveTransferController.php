<?php

// app/Http/Controllers/ReceiveTransferController.php
namespace App\Http\Controllers;

use App\Models\ReceiveTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\EntryController;
class ReceiveTransferController extends Controller
{
    public function index()
    {
        return response()->json(ReceiveTransfer::with(['entry_id', 'currency_id', 'account_id', 'agent_account_id'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|integer',
            'amount' => 'required|numeric',
            'currency_id' => 'required|integer',
            'receiverName' => 'required|string|max:255',
            'senderName' => 'required|string|max:255',
            'account_id' => 'required|integer',
            'agent_account_id' => 'required|integer',
        ]);

        $receiveTransfer = ReceiveTransfer::create($request->all());

        return response()->json($receiveTransfer, 201);
    }

    public function show($id)
    {
        return response()->json(ReceiveTransfer::with(['entry_id', 'currency_id', 'account_id', 'agent_account_id'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $receiveTransfer = ReceiveTransfer::findOrFail($id);

        $receiveTransfer->update($request->all());

        return response()->json($receiveTransfer);
    }

    public function destroy($id)
    {
        ReceiveTransfer::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}

