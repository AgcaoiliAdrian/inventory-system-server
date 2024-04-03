<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Crate;
use App\Models\BarcodeDetails;
use App\Models\CrateStock;
use App\Models\TempBatchIn;
use App\Helpers\Helpers;

class CrateStockInController extends Controller
{
    public function index(){
        try {
            $data = BarcodeDetails::with(['variant', 'brand', 'thickness', 'glue', 'grade'])
                ->join('crate_stock', 'barcode_details.id', '=', 'crate_stock.barcode_id')
                ->whereIn('barcode_details.id', function ($query) {
                    $query->select('barcode_id')->from('crate_stock');
                })
                ->where('crate_stock.status', 'in')
                ->select('brand_id', 'variant_id', 'thickness_id', 'glue_type_id', 'grade_id',
                 'manufacturing_date', 'batch_number', 'status', 'crate_stock.created_at') // Select all columns from barcode_details
                ->distinct('batch_number') // Select distinct based on these columns
                ->get();        
    
            return response()->json($data, 200);
    
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }     
      

    public function show($barcode, Request $request){
        try {
            $details = Brand::with('glue', 'thickness', 'variant', 'grade')->find($barcode);

            return response()->json($details, 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    
    public function IndexTempBatchIn(){
        try {
            $data = TempBatchIn::with(['brand', 'variant', 'grade', 'barcode'])->get();

            return response()->json($data);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function tempBatchStockIn($barcode, Request $request){
        try {
            $scanned = BarcodeDetails::where('barcode_number', $barcode)->first();

            $existing_record = TempBatchIn::where('barcode_id', $scanned->id)->first();
    
            // Initialize status with 'Success'
            $status = 'success';
    
            // Check if there's an existing record with the same barcode_id
            if ($existing_record) {
                // If exists, set status to 'Failed'
                return response()->json(['message'=>'Barcode already scanned'], 201);
                
            } else {
                // Check if brand_id and variant_id are the same
                $previous_data = TempBatchIn::first();
                if ($previous_data && ($previous_data->brand_id != $scanned->brand_id || $previous_data->variant_id != $scanned->variant_id)) {
                    // If not the same, set status to 'Failed'
                    $status = 'failed';
                }
                
                // Save the new data
                TempBatchIn::create([
                    'barcode_id' => $scanned->id,
                    'brand_id' => $scanned->brand_id,
                    'grade_id' => $request->grade_id,
                    'variant_id' => $scanned->variant_id,
                    'quantity' => 1,
                    'manufacturing_date' => now(),
                    'status' => $status,
                ]);
            }
    
            return response()->json(['message' => 'New Record added', 'status' => $status], 200);
    
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }    
    

    public function saveBatchStockIn(Request $request){
        try {
            $batch_in = TempBatchIn::where('status', 'success')->get();
            
            // Generate batch number using the helper function and pass the encoding type
            $batch_number = Helpers::generateBatchNumber($request->encoding_type);


            foreach ($batch_in as $data){
                $barcode = BarcodeDetails::findOrFail($data -> barcode_id);
                $barcode->update([
                    'grade_id' => $data->grade_id
                ]);
    
                $crate_stock = new CrateStock();
                $crate_stock -> barcode_id = $data -> barcode_id;
                // $crate_stock -> grade_id = $data -> grade_id;
                $crate_stock -> quantity = 1;
                $crate_stock -> manufacturing_date =  Carbon::now();
                $crate_stock -> batch_number = $batch_number;
                $crate_stock -> status = 'in';
                $crate_stock->save();

                if($crate_stock){
                    TempBatchIn::truncate();
                }
            }
        
            return response()->json(['message' => 'Success', ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }  
    
    public function delete($id){
        try {
            $brand = TempBatchIn::find($id)->delete();

            return response('Success', 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
