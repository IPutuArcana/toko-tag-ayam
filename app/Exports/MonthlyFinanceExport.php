<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyFinanceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $month;
    protected $year;

    // Receive the Month and Year from the Controller
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // 1. The Query: Get only transactions for the selected month
    public function query()
    {
        return Transaction::query()
            ->whereYear('transaction_date', $this->year)
            ->whereMonth('transaction_date', $this->month)
            ->orderBy('transaction_date', 'asc');
    }

    // 2. The Headings: What shows up in Row 1
    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal',
            'Keterangan',
            'Tipe',
            'Masuk (Income)',
            'Keluar (Expense)',
            'Saldo', // Optional, hard to calculate row-by-row in SQL, maybe skip for now or keep simple
        ];
    }

    // 3. Mapping: How to format each row
    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->transaction_date,
            $transaction->description,
            strtoupper($transaction->type),
            // Logic: If Income, put in Income column, else 0
            $transaction->type == 'income' ? $transaction->amount : 0,
            // Logic: If Expense, put in Expense column, else 0
            $transaction->type == 'expense' ? $transaction->amount : 0,
        ];
    }

    // 4. Styling: Make the header bold (Commercial Look)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}