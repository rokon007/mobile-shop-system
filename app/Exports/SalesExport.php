<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Date',
            'Customer',
            'Items Count',
            'Subtotal',
            'Tax',
            'Total Amount',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_no,
            $sale->sale_date->format('M d, Y'),
            $sale->customer->name ?? 'Walk-in Customer',
            $sale->items->count(),
            number_format($sale->subtotal, 2),
            number_format($sale->tax_amount, 2),
            number_format($sale->total_amount, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],

            // Styling specific cells
            'A1:G1' => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F5F5F5']]],
        ];
    }
}
