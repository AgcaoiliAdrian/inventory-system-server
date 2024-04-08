<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Panel;
use App\Models\BarcodeDetails;
use App\Models\TempPanelIn;

class PanelStockInController extends Controller
{
    public function index(){
        try {
            $data = BarcodeDetails::with(['variant', 'brand', 'thickness', 'grade'])
            ->join('panel_stock', 'barcode_details.id', '=', 'panel_stock.barcode_id')
            ->whereIn('barcode_details.id', function ($query) {
                $query->select('barcode_id')->from('panel_stock');
            })
            ->where('panel_stock.status', 'in')
            ->get();        
    
            return response()->json($data, 200);
    
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }    

    public function IndexTempPanelIn(){
        try {
            $data = TempPanelIn::with(['brand', 'variant', 'grade', 'barcode.thickness', 'barcode.glue'])->get();
    
            return response()->json($data);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th
            ]);
        }
    }              

    public function panelStockIn($id, Request $request){
        try {
            $barcode = BarcodeDetails::findOrFail($id);
            $barcode->update([
                'glue_type_id' => $request->glue_type_id,
                'thickness_id' => $request->thickness_id,
            ]);

            $existingPanel = Panel::where('barcode_id', $barcode -> id)->first();

            if ($existingPanel) {
                return response()->json([
                    'message' => 'Barcode already Scanned'
                ], 400);
            }

            $panel_stock = new Panel();
            $panel_stock -> barcode_id = $barcode -> id;
            $panel_stock -> grade_id = $request -> grade_id;
            $panel_stock -> grader = $request -> grader;
            $panel_stock -> manufacturing_date = Carbon::now();
            $panel_stock -> quantity = 1;
            $panel_stock -> price = $request -> price;
            $panel_stock -> status = 'in';
            $panel_stock -> save();

            return response('Success');

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function savePanelStockIn(){
        try {
            $temp_panel = TempPanelIn::where('status', 'success')->get();

            // return $temp_panel;

            foreach($temp_panel as $data){
                $barcode = BarcodeDetails::findOrFail($data -> barcode_id);
                $barcode->update([
                    'grade_id' => $data->grade_id
                ]);
    
                $panel = new Panel();
                $panel -> barcode_id = $data -> barcode_id;
                $panel -> quantity = 1;
                $panel -> grader = $data -> grader;
                $panel -> manufacturing_date = Carbon::now();
                $panel -> status = 'in';
                $panel -> save();
            }
    
          
            if($panel){
                TempPanelIn::truncate();
                return response()->json(['message' => 'Panel stocked in succesfully saved'], 200);
            }

            return response()->json(['message' => 'Failed to save panel stock in'], 401);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function tempPanelStockIn($barcode, Request $request){
        try {
            
            $scanned = BarcodeDetails::where('barcode_number', $barcode)->first();
            $existing_record = TempPanelIn::where('barcode_id', $scanned->id)->first();

            // Initialize status with 'Success'
            $status = 'success';
    
            // Check if brand_id and variant_id are the same
            // if ($previous_data ) {
            //     // If not the same, set status to 'Failed'
            //     $status = 'failed';
            // }

            if($existing_record) {
                return response()->json(['message'=>'Barcode already scanned'], 201);
            }else {
                $previous_data = TempPanelIn::first();

                // Save the new data with the determined status
                $panel = TempPanelIn::create([
                    'barcode_id' => $scanned->id,
                    'brand_id' => $scanned->brand_id,
                    'grade_id' => $request->grade_id,
                    'grader' => $request -> grader,
                    'variant_id' => $scanned->variant_id,
                    'quantity' => 1,
                    'price' => $request->price,
                    'manufacturing_date' => now(),
                    'status' => $status
                ]);
            }
    
            return response()->json(['message' => 'Success']);
    
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function delete($id){
        try {
            $brand = TempPanelIn::find($id)->delete();

            return response('Success', 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
