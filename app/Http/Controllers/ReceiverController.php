<?php

namespace App\Http\Controllers;

use App\Models\Receiver;
use Illuminate\Http\Request;

class ReceiverController extends Controller
{
    public function index()
    {
        $receivers = Receiver::all();
        return response()->json($receivers);
    }

    public function store(Request $request)
    {
        $receiver = Receiver::create($request->all());
        return response()->json($receiver, 201);
    }

    public function show($id)
    {
        $receiver = Receiver::findOrFail($id);
        return response()->json($receiver);
    }

    public function update(Request $request, $id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->update($request->all());
        return response()->json($receiver);
    }

    public function destroy($id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->delete();
        return response()->json(null, 204);
    }
}

