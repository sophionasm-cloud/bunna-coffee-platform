<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvestmentOpportunity;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index()
    {
        $opportunities = InvestmentOpportunity::where('status', 'open')->get();
        return response()->json($opportunities);
    }

    public function show($id)
    {
        $opportunity = InvestmentOpportunity::findOrFail($id);
        return response()->json($opportunity);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'location' => 'required|string',
            'min_investment' => 'required|numeric|min:0',
            'expected_return_pct' => 'nullable|numeric',
            'duration_months' => 'nullable|integer',
            'status' => 'required|in:open,closed,funded',
        ]);

        $opportunity = InvestmentOpportunity::create($data);
        return response()->json($opportunity, 201);
    }

    public function update(Request $request, $id)
    {
        $opportunity = InvestmentOpportunity::findOrFail($id);
        $opportunity->update($request->all());
        return response()->json($opportunity);
    }

    public function destroy($id)
    {
        $opportunity = InvestmentOpportunity::findOrFail($id);
        $opportunity->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}