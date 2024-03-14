<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempBatchOut;
use App\Models\Crate;
use App\Models\BarcodeDetails;

class CrateStockOutController extends Controller
{
    public function IndexTempBatchOut(){
        try {
            $data = BarcodeDetails::with(['variant', 'brand', 'thickness', 'grade'])
                ->join('crate_stock as cs1', 'cs1.barcode_id', '=', 'barcode_details.id')
                ->join('temp_batch_out', 'temp_batch_out.batch_number', '=', 'cs1.batch_number')
                ->whereIn('temp_batch_out.batch_number', function ($query) {
                    $query->select('batch_number')->from('temp_batch_out');
                })
                ->get();
            
            return $data;
            
            
        } catch (\Throwable $th) {
           return response()->json([
                'message' => $th -> getMessage()
           ]);
        }
    }

    public function tempBatchStockOut($id, Request $request){
        try {
            $batch_number = Crate::where('barcode_id', $id)->pluck('batch_number')->first();
            $existingBatch = TempBatchOut::where('batch_number', $batch_number)->first();

            if ($existingBatch) {
                return response()->json([
                    'message' => 'Crate already exist'
                ], 400);
            }

           $temp_batch_out = new TempBatchOut();
           $temp_batch_out -> batch_number = $batch_number;
           $temp_batch_out -> save();

           return response('Success');
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
           ]);
        }
    }

    public function saveBatchStockOut(){
        try {
            $temp_batch_out = TempBatchOut::pluck('batch_number')->toArray();

            // Update all Crates with matching batch numbers
            Crate::whereIn('batch_number', $temp_batch_out)
                ->update(['status' => 'out']);

            // Truncate the TempBatchOut table
            TempBatchOut::truncate();

            return response('Success');

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}