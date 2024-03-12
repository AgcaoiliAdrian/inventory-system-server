<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Panel;
use App\Models\BarcodeDetails;
use App\Models\CrateStock;
use App\Models\TempBatchIn;
use App\Helpers\Helpers;

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

    public function batchStockIn($id, Request $request){
        try {
            $scanned = BarcodeDetails::findOrFail($id);
            $previous_data = TempBatchIn::first();
    
            // Initialize status with 'Success'
            $status = 'Success';
    
            // Check if brand_id and variant_id are the same
            if ($previous_data && ($previous_data->brand_id != $scanned->brand_id || $previous_data->variant_id != $scanned->variant_id)) {
                // If not the same, set status to 'Failed'
                $status = 'Failed';
            }
    
            // Save the new data with the determined status
            TempBatchIn::create([
                'barcode_id' => $scanned->id,
                'brand_id' => $scanned->brand_id,
                'grade_id' => $request->grade_id,
                'variant_id' => $scanned->variant_id,
                'glue_type_id' => $request->glue_type_id,
                'thickness_id' => $request->thickness_id,
                'quantity' => 1,
                'manufacturing_date' => now(),
                'status' => $status,
            ]);
    
            return response()->json(['message' => 'Success']);
    
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }    
    

    public function stockIn(Request $request){
        try {
            $batch_in = TempBatchIn::where('status', 'Success')->get();

            foreach ($batch_in as $data){
                $barcode = BarcodeDetails::findOrFail($data -> barcode_id);
                $barcode->update([
                    'glue_type_id' => $data->glue_type_id,
                    'thickness_id' => $data->thickness_id,
                ]);

                
            }
            
            // if ($request->encoding_type === 0 || $request->encoding_type === 1) {
            //     // Check if there's already a panel entry for this barcode
            //     $existingPanel = Panel::where('barcode_id', $barcode->id)->first();
    
            //     // If there's an existing panel, return an error response
            //     if ($existingPanel) {
            //         return response()->json([
            //             'message' => 'Double entry is not allowed'
            //         ], 400);
            //     }
            // }
    
            // $panel_stock = new Panel();
            // $panel_stock -> barcode_id = $barcode -> id;
            // $panel_stock -> grade_id = $request -> grade_id;
            // $panel_stock -> manufacturing_date = Carbon::now();
            // $panel_stock -> quantity = 1;
            // $panel_stock -> is_batch = $request -> encoding_type;
            // // $panel_stock -> status = $request -> status;
            // $panel_stock -> save();
    
            // if ($request->encoding_type === 1) {
            //     // Generate batch number using the helper function and pass the encoding type
            //     $batch_number = Helpers::generateBatchNumber($request->encoding_type);
            // } else {
            //     // Generate batch number using the helper function and pass the encoding type
            //     $batch_number = Helpers::generateBatchNumber($request->encoding_type);
            // }

            // if($request -> encoding_type === 1){
            //     $crate_stock = new CrateStock();
            //     $crate_stock->panel_stock_id = $panel_stock->id;
            //     $crate_stock->batch_number = $batch_number;
            //     $crate_stock->save(); 
            // }           
    
            return response('Success');
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }        
}
