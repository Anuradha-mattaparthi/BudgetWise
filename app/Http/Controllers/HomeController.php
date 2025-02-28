<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $familyDetail = $user->familyDetails;

        // Default values
        $income = $expenses = 0;
        $monthlyIncome = [];
        $monthlyExpenses = [];

        if ($familyDetail) {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $income = $familyDetail->incomes()
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->sum('amount');

            $expenses = $familyDetail->expenses()
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->sum('amount');
            // Fetch income & expenses grouped by month (for graphs)
                $currentYear = now()->year; // Get the current year dynamically

                $monthlyIncome = $familyDetail->incomes()
                ->select('month', DB::raw('SUM(amount) as total'))
                ->where('year', $currentYear)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

                $monthlyExpenses = $familyDetail->expenses()
                ->select('month', DB::raw('SUM(amount) as total'))
                ->where('year', $currentYear)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();
                    }

        return view('dashboard', compact('income', 'expenses', 'monthlyIncome', 'monthlyExpenses'));
    }
}
