<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // 1. The Dashboard View (The Report)
    public function index()
    {
        // Commercial Math: Calculate real-time stats
        $totalIncome  = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $netProfit    = $totalIncome - $totalExpense;

        // Get history sorted by newest first
        $transactions = Transaction::latest()->paginate(10);

        return view('finance.index', compact('transactions', 'totalIncome', 'totalExpense', 'netProfit'));
    }

    // 2. The Action: Add Expense (Buying Stock/Bills)
    public function storeExpense(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric|min:1',
            'date'        => 'required|date',
        ]);

        Transaction::create([
            'type'             => 'expense', // Manual entry is always an expense here
            'amount'           => $request->amount,
            'description'      => $request->description,
            'transaction_date' => $request->date,
            'order_id'         => null, // No order linked
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat!');
    }
}