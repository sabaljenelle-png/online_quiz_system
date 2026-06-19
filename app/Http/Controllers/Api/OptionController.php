<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_text' => 'required|string',
            'is_correct' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_correct'] = $request->boolean('is_correct');
        $option = Option::create($validated);

        return response()->json($option, 201);
    }

    public function update(Request $request, Option $option)
    {
        $validated = $request->validate([
            'option_text' => 'sometimes|required|string',
            'is_correct' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($request->has('is_correct')) {
            $validated['is_correct'] = $request->boolean('is_correct');
        }

        $option->update($validated);

        return response()->json($option);
    }

    public function destroy(Option $option)
    {
        $option->delete();

        return response()->json(['message' => 'Option deleted successfully.']);
    }
}
