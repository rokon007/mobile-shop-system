<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $inventories;

    public function __construct($inventories)
    {
        $this->inventories = $inventories;
    }

    public function collection()
    {
        return $this->inventories;
    }

    public function headings(): array
    {
        return [
            'Product',
            'Brand',
            'Category',
            'IMEI/Serial Number',
            'Configuration',
            'Price',
        ];
    }

    public function map($inventory): array
    {
        $attributes = json_decode($inventory->attribute_combination, true) ?? [];
        $configuration = '';

        foreach ($attributes as $key => $value) {
            $filter = \App\Models\Filter::find($key);
            $optionValue = \App\Models\FilterOption::find($value);

            if ($filter && $optionValue) {
                $configuration .= $filter->name . ': ' . $optionValue->value . ', ';
            }
        }

        // Remove trailing comma and space
        $configuration = rtrim($configuration, ', ');

        // Determine what to show in the IMEI/Serial Number column
        $identifier = 'N/A';
        if ($inventory->imei) {
            $identifier = $inventory->imei;
        } elseif ($inventory->serial_number) {
            $identifier = $inventory->serial_number;
        }

        return [
            $inventory->product->name ?? 'N/A',
            $inventory->product->brand->name ?? 'N/A',
            $inventory->product->category->name ?? 'N/A',
            $identifier,
            $configuration,
            number_format($inventory->selling_price, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],

            // Styling specific cells
            'A1:F1' => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F5F5F5']]],
        ];
    }
}
