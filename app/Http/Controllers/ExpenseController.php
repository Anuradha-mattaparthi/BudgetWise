<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\FamilyDetail;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function create(FamilyDetail $familyDetail)
    {
        return view('expenses.create', compact('familyDetail'));
    }

    // Store the expense details
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'family_detail_id' => 'required|exists:family_details,id',
            'expense_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0'


        ]);

        // Save data
        Expense::create([
            'family_detail_id' => $request->family_detail_id,
            'expense_name' => $request->expense_name,
            'amount' => $request->amount,
            'year' => $request->year,
            'month' => $request->month
        ]);

        return response()->json(['message' => 'Expense added successfully!']);
    }
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update([
            'expense_name' => $request->expense_name,
            'amount' => $request->amount,
        ]);

        return response()->json(['message' => 'Expense updated successfully!']);
    }

    // Delete an Expense
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully!']);
    }
}
