<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\BarcodeDetails;

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
                ->leftJoin('crate_stock', 'barcode_details.id', '=', 'crate_stock.barcode_id')
                ->leftJoin('panel_stock', 'barcode_details.id', '=', 'panel_stock.barcode_id')
                ->select(
                    'barcode_details.brand_id',
                    'barcode_details.variant_id',
                    'barcode_details.glue_type_id',
                    'barcode_details.thickness_id',
                    'barcode_details.grade_id',
                    // DB::raw('COUNT(DISTINCT crate_stock.id) as crate_stock_count'),
                    // DB::raw('COUNT(DISTINCT panel_stock.id) as panel_stock_count')
                )
                // ->groupBy(
                //     'barcode_details.brand_id',
                //     'barcode_details.variant_id',
                //     'barcode_details.glue_type_id',
                //     'barcode_details.thickness_id',
                //     'barcode_details.grade_id',
                // )
                ->where('brand_id', 1)
                ->get();
    
            return response()->json($data, 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
