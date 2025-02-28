<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamilyDetail;
use Illuminate\Support\Facades\Auth;
use Response;
class FamilyDetailController extends Controller
{
    public function create()
    {

        return view('family-details.create');
    }
    public function show($id, Request $request)
{
    $familyDetail = FamilyDetail::findOrFail($id);

        // Default month and year to current if not provided in the request
        $month = $request->get('month', date('m'));  // Default to current month
        $year = $request->get('year', date('Y'));  // Default to current year
        //\Log::info("Filtering expenses for month: {$month}, year: {$year}");
       // dd($month, $year);

        // Eager load expenses and incomes, filtering by month and year
        $expenses = $familyDetail->expenses()
    ->whereRaw('CAST(month AS UNSIGNED) = ?', [$month])
    ->whereRaw('CAST(year AS UNSIGNED) = ?', [$year])
    ->get();
         //dd($expenses);
        $incomes = $familyDetail->incomes()
        ->whereRaw('CAST(month AS UNSIGNED) = ?', [$month])
        ->whereRaw('CAST(year AS UNSIGNED) = ?', [$year])
            ->get();


        // Calculate total expenses and incomes
        $totalExpenses = $expenses->sum('amount');
        $totalIncomes = $incomes->sum('amount');

        // Return the view with filtered expenses and incomes
        return view('family-details.show', compact('familyDetail', 'expenses', 'incomes', 'totalExpenses', 'totalIncomes', 'month', 'year'));
    }




    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'family_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'relationship' => 'nullable|string|max:255',
            'spouse_name' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
            'children' => 'nullable|nemeric',
        ]);

        // Save the data

        FamilyDetail::create([
            'user_id' => Auth::id(), // Assuming user is authenticated
            'family_name' => $request->family_name,
            'age' => $request->age,
            'relationship' => $request->relationship,
            'spouse_name' => $request->spouse_name,
            'salary' => $request->salary,
            'children'=>$request->children,
        ]);

        return response()->json(['message' => 'Family details added successfully!']);
    }
    public function update(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'family_name' => 'required|string|max:255',
        'age' => 'required|integer',
        'relationship' => 'required|string|max:255',
        'spouse_name' => 'nullable|string|max:255',
        'children' => 'required|numeric',
    ]);

    // Find the family member by ID
    $familyDetail = FamilyDetail::findOrFail($id);

    // Update the family details
    $familyDetail->update([
        'family_name' => $request->input('family_name'),
        'age' => $request->input('age'),
        'relationship' => $request->input('relationship'),
        'spouse_name' => $request->input('spouse_name'),
        'children' => $request->input('children'),
    ]);

    // Redirect with success message
    return response()->json(['message' => 'Family details updated successfully!']);
}

    // Other methods...

    public function downloadExpenses(Request $request, $familyId)
{
    // Get family details
    $familyDetail = FamilyDetail::findOrFail($familyId);

    // Get the month and year from the request, or default to the current month and year
    $month = $request->get('month', date('m'));  // Default to current month
    $year = $request->get('year', date('Y'));  // Default to current year
//dd($month,$year);
    // Use whereRaw to filter the expenses based on the month and year columns
    $expenses = $familyDetail->expenses()
                             ->whereRaw('CAST(month AS UNSIGNED) = ?', [$month])
                             ->whereRaw('CAST(year AS UNSIGNED) = ?', [$year])
                             ->get();

    if ($expenses->isEmpty()) {
        return response()->json(['message' => 'No expenses found for the selected month/year']);
    }

    $csvContent = "Expense Name,Amount\n";
    foreach ($expenses as $expense) {
        $csvContent .= "{$expense->expense_name},{$expense->amount}\n";
    }

    // Set headers for CSV download
    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=expenses_{$familyDetail->id}_{$month}_{$year}.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0",
    ];

    // Return the CSV as a downloadable file
    return response($csvContent, 200, $headers);
}

public function downloadIncomes(Request $request, $familyId)
{
    // Get family details
    $familyDetail = FamilyDetail::findOrFail($familyId);

    // Get the month and year from the request, or default to the current month and year
    $month = $request->get('month', date('m'));  // Default to current month
    $year = $request->get('year', date('Y'));  // Default to current year

    // Use whereRaw to filter the incomes based on the month and year columns
    $incomes = $familyDetail->incomes()
                             ->whereRaw('CAST(month AS UNSIGNED) = ?', [$month])
                             ->whereRaw('CAST(year AS UNSIGNED) = ?', [$year])
                             ->get();

    if ($incomes->isEmpty()) {
        return response()->json(['message' => 'No incomes found for the selected month/year']);
    }

    // Prepare CSV content
    $csvContent = "Income Source,Amount\n";
    foreach ($incomes as $income) {
        $csvContent .= "{$income->source},{$income->amount}\n";
    }

    // Set headers for CSV download
    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=incomes_{$familyDetail->id}_{$month}_{$year}.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0",
    ];

    // Return the CSV as a downloadable file
    return response($csvContent, 200, $headers);
}


}
