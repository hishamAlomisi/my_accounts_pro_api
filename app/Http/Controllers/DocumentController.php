<?php

// app/Http/Controllers/DocumentController.php
namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return response()->json(Document::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $document = Document::create($request->all());

        return response()->json($document, 201);
    }

    public function show($id)
    {
        return response()->json(Document::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $document->update($request->all());

        return response()->json($document);
    }

    public function destroy($id)
    {
        Document::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
