<?php

namespace App\Exports;

use App\Models\BarcodeDetails;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenerateReportExport implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $formattedData = [];

        foreach ($this->data as $item) {
            $formattedData[] = [
                'Brand Name' => $item->brand->brand_name,
                'Variant' => $item->variant ? $item->variant->variant_name : null,
                'Glue Type' => 'Type' . ' '. $item->glue->type,
                'Thickness' => $item->thickness->value . ' ' . $item->thickness->unit,
                'Grade' => $item->grade->grading,
                'Grader' => $item->grader,
                'Barcode No.' => $item->barcode_number,
                'Manufacturing Date' => date('F j, Y', strtotime($item->manufacturing_date)),
                'Stock-in' => $item->stockIn,
                'Stock-out' => $item->stockOut ? $item->stockOut : '0',
                '' => '',
            ];
        }

        $formattedData[] = [
            'Brand Name' => '',
            'Variant' => '',
            'Glue Type' => '',
            'Thickness' => '',
            'Grade' => '',
            'Grader' => '',
            'Barcode No.' => '',
            'Manufacturing' => 'TOTAL'
        ];

        return collect($formattedData);
    }

    public function headings(): array
    {
        return [
            'Brand Name',
            'Variant',
            'Glue Type',
            'Thickness',
            'Grade',
            'Grader',
            'Barcode No.',
            'Manufacturing Date',
            'Stock-in',
            'Stock-out',
            '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center align all text and add borders
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
    }
}
