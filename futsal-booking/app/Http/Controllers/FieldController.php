<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;

class FieldController extends Controller
{
    public function index()
    {
        return view('fields.index', [
            'fields' => Field::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price_per_hour' => 'required|numeric|min:0'
        ]);

        return response()->json(Field::create($request->all()));
    }

    public function update(Request $request, Field $field)
    {
        $request->validate([
            'name' => 'required',
            'price_per_hour' => 'required|numeric|min:0'
        ]);

        $field->update($request->all());

        return response()->json($field);
    }

    public function destroy(Field $field)
    {
        $field->delete();
        return response()->json(['success' => true]);
    }
}
