<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\BarcodeDetails;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenerateReportExport;

class GenerateReportController extends Controller
{
    public function generate(Request $request){
        try {
            $data = BarcodeDetails::with('brand', 'variant', 'glue', 'thickness', 'grade')
            ->whereHas('glue', function ($query) {
                $query->whereNotNull('glue_type_id');
            })
            ->whereHas('thickness', function ($query) {
                $query->whereNotNull('thickness_id');
            })
            ->whereHas('panelStock')
            ->leftJoin('panel_stock', 'barcode_details.id', '=', 'panel_stock.barcode_id')
            ->select(
                'barcode_details.brand_id',
                'barcode_details.variant_id',
                'barcode_details.glue_type_id',
                'barcode_details.thickness_id',
                'barcode_details.grade_id',
                'barcode_details.barcode_number',
                'grader',
                'manufacturing_date',
                DB::raw("COUNT(DISTINCT CASE WHEN panel_stock.status = 'in' THEN panel_stock.id END) as `stockIn`"),
                DB::raw("COUNT(DISTINCT CASE WHEN panel_stock.status = 'out' THEN panel_stock.id END) as `stockOut`")
            )
            ->where('brand_id', 3)
            ->groupBy(
                'barcode_details.brand_id',
                'barcode_details.variant_id',
                'barcode_details.glue_type_id',
                'barcode_details.thickness_id',
                'barcode_details.grade_id',
                'barcode_details.barcode_number',
                'grader',
                'manufacturing_date'
            )
            ->orderBy('barcode_details.barcode_number', 'asc')
            ->get();
            
            // return $data;      
    
            return Excel::download(new GenerateReportExport($data), 'Report.xlsx');

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
