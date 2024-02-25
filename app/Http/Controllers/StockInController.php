<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Panel;
use App\Models\BarcodeDetails;
use App\Models\CrateStock;
use Illuminate\Support\Str;

class StockInController extends Controller
{
    public function show($id, Request $request){
        try {
            $details = Brand::with('glue', 'thickness', 'variant', 'grade')->find($id);

            return response()->json($details);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th
            ]);
        }
    }

    public function stockIn($id, Request $request){
        try {
    
            $barcode = BarcodeDetails::findOrFail($id);
            $barcode->update([
                'glue_type_id' => $request->glue_id,
                'thickness_id' => $request->thickness_id,
            ]);
            
            if ($request->encoding_type === 0) {
                // Check if there's already a panel entry for this barcode
                $existingPanel = Panel::where('barcode_id', $barcode->id)->first();
    
                // If there's an existing panel, return an error response
                if ($existingPanel) {
                    return response()->json([
                        'message' => 'Individual encoding type allows only one insertion.'
                    ], 400);
                }
            }
    
            $panel_stock = new Panel();
            $panel_stock -> barcode_id = $barcode -> id;
            $panel_stock -> grade_id = $request -> grade_id;
            $panel_stock -> manufacturing_date = Carbon::now();
            $panel_stock -> quantity = 1;
            $panel_stock -> is_batch = $request -> encoding_type;
            $panel_stock -> save();

            if($request -> encoding_type === 1){
                $batch_number = Str::random(8); //Helper to be coded for generating Batch Number
                $crate_stock = new CrateStock();
                $crate_stock -> panel_stock_id = $panel_stock -> id;
                $crate_stock -> batch_number = $batch_number;
                $crate_stock -> save();
            }
    
            return response('Success');

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }    
}
