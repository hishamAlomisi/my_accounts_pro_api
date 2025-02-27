<?php

// app/Http/Controllers/CardController.php
namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        return response()->json(Card::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
    
            'image1' => 'required|string',
    
        ]);

        $card = Card::create($request->all());

        return response()->json($card, 201);
    }

    public function show($id)
    {
        return response()->json(Card::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);

        $card->update($request->all());

        return response()->json($card);
    }

    public function destroy($id)
    {
        Card::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
