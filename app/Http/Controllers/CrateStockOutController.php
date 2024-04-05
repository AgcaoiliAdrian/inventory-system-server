<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempBatchOut;
use App\Models\Crate;
use App\Models\Panel;
use App\Models\BarcodeDetails;

class CrateStockOutController extends Controller
{
    public function index(){
        try {
            $data = BarcodeDetails::with(['variant', 'brand', 'thickness', 'glue', 'grade'])
                ->join('crate_stock', 'barcode_details.id', '=', 'crate_stock.barcode_id')
                ->whereIn('barcode_details.id', function ($query) {
                    $query->select('barcode_id')->from('crate_stock');
                })
                ->where('crate_stock.status', 'out')
                ->select('brand_id', 'variant_id', 'thickness_id', 'glue_type_id', 'grade_id',
                 'manufacturing_date', 'batch_number', 'status', 'crate_stock.created_at') // Select all columns from barcode_details
                ->distinct('batch_number') // Select distinct based on these columns
                ->get();        
    
            return response()->json($data, 200);
    
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function IndexTempBatchOut(){
        try {
            $data = BarcodeDetails::with(['variant', 'brand', 'thickness', 'grade'])
                ->join('crate_stock as cs1', 'cs1.barcode_id', '=', 'barcode_details.id')
                ->join('temp_batch_out', 'temp_batch_out.batch_number', '=', 'cs1.batch_number')
                ->whereIn('temp_batch_out.batch_number', function ($query) {
                    $query->select('batch_number')->from('temp_batch_out');
                })
                ->get();
            
            return response()->json($data, 200);
            
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function tempBatchStockOut($barcode, Request $request){
        try {
            $scanned = BarcodeDetails::where('barcode_number', $barcode)->first();

            $batch_number = Crate::where('barcode_id', $scanned->id)->pluck('batch_number')->first();

            if (!$batch_number) {
                return response()->json([
                    'message' => 'Panel scanned does not belong to any crate'
                ], 404);
            }

            $existingBatch = TempBatchOut::where('batch_number', $batch_number)->first();

            if ($existingBatch) {
                return response()->json([
                    'message' => 'Crate already exist'
                ], 400);
            }

           $temp_batch_out = new TempBatchOut();
           $temp_batch_out -> batch_number = $batch_number;
           $temp_batch_out -> save();
           
           return response()->json([
            'message' => 'New batch added to stockout list'], 200);
        
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
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

            return response('Success', 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function insertOne($barcode, Request $request){
        try {
            $scanned = BarcodeDetails::where('barcode_number', $barcode)->first();
            $batch_number = Crate::where('id', $request->crate_stock_id)->pluck('batch_number')->first();
            $panel = Panel::where('barcode_id', $scanned->id)->get();

            foreach ($panel as $data) {
                $crate = new Crate();
                $crate -> barcode_id = $data -> barcode_id;
                $crate -> quantity = $data -> quantity;
                $crate -> manufacturing_date = $data -> manufacturing_date;
                $crate -> batch_number =  $batch_number;
                $crate -> status = 'out';
                $crate -> save();

                if($crate){
                    Panel::where('id', $data->id)->delete();
                }
            }

            return response()->json('Panel successfully inserted to crate', 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function delete($id){
        try {
            $brand = TempBatchOut::find($id)->delete();

            return response('Success', 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}