<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\BarcodeDetails;

use Illuminate\Http\Request;

class DataTableController extends Controller
{
    public function index(Request $request){
        try {
            $data = BarcodeDetails::with('brand', 'variant', 'glue', 'thickness', 'grade')
                ->whereHas('glue', function ($query) {
                    $query->whereNotNull('glue_type_id');
                })
                ->whereHas('thickness', function ($query) {
                    $query->whereNotNull('thickness_id');
                })
                ->leftJoin('crate_stock', 'barcode_details.id', '=', 'crate_stock.barcode_id')
                ->whereNotNull('crate_stock.barcode_id') // Add this condition
                ->select(
                    'barcode_details.brand_id',
                    'barcode_details.variant_id',
                    'barcode_details.glue_type_id',
                    'barcode_details.thickness_id',
                    'barcode_details.grade_id',
                    'crate_stock.batch_number',
                    DB::raw('COUNT(crate_stock.id) as crate_count'),
                    DB::raw('SUM(case when crate_stock.status = "in" then 1 else 0 end) as total_in'),
                    DB::raw('SUM(case when crate_stock.status = "out" then 1 else 0 end) as total_out')
                    )
                ->groupBy(
                    'barcode_details.brand_id',
                    'barcode_details.variant_id',
                    'barcode_details.glue_type_id',
                    'barcode_details.thickness_id',
                    'barcode_details.grade_id',
                    'crate_stock.batch_number'
                )
                ->get();
    
            return $data;
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }
}
