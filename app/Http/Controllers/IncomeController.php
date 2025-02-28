<?php

namespace App\Http\Controllers;

use App\Models\FamilyDetail;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function create(FamilyDetail $familyDetail)
    {
        return view('incomes.create', compact('familyDetail'));
    }

    // Store a new income record
    public function store(Request $request)
    {
        $request->validate([
            'family_detail_id' => 'required|exists:family_details,id',
            'amount' => 'required|numeric',
            'source' => 'required|string|max:255',
        ]);
        Income::create([
            'family_detail_id' => $request->family_detail_id,
            'amount' => $request->amount,
            'source' => $request->source,
            'month' => $request->month,
            'year' => $request-> year,
        ]);

        return response()->json(['message' => 'Income added successfully!']);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string|max:255',
        ]);

        $income = Income::findOrFail($id);
        $income->update([
            'amount' => $request->amount,
            'source' => $request->source,
        ]);

        return response()->json(['message' => 'Income updated successfully!']);
    }

    // Delete an income record
    public function destroy($id)
{
    $income = Income::findOrFail($id); // Find income record by ID
    $income->delete(); // Delete the record

    return response()->json(['message' => 'Income deleted successfully']);
}


}
