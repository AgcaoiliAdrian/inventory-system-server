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
    
            return response()->json($data);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }

    public function savePanelStockIn(){
        $temp_panel = TempPanelIn::where('status', 'success')->get();

        foreach($temp_panel as $data){
            $barcode = BarcodeDetails::findOrFail($data -> barcode_id);
            $barcode->update([
                'glue_type_id' => $data->glue_type_id,
                'thickness_id' => $data->thickness_id,
                'grade_id' => $data->grade_id
            ]);

            $panel = new Panel();
            $panel -> barcode_id = $data -> barcode_id;
            $panel -> quantity = 1;
            $panel -> price = $data -> price;
            $panel -> manufacturing_date = Carbon::now();
            $panel -> status = 'in';
            $panel -> save();
        }

        if($panel){
            TempPanelIn::truncate();
        }

        return response('Success');
    }

    public function tempPanelStockIn($id, Request $request){
        try {
            $scanned = BarcodeDetails::findOrFail($id);
            $previous_data = TempPanelIn::first();

            // Initialize status with 'Success'
            $status = 'success';
    
            // Check if brand_id and variant_id are the same
            if ($previous_data && ($previous_data->brand_id != $scanned->brand_id || $previous_data->variant_id != $scanned->variant_id)) {
                // If not the same, set status to 'Failed'
                $status = 'failed';
            }
    
            // Save the new data with the determined status
            TempPanelIn::create([
                'barcode_id' => $scanned->id,
                'brand_id' => $scanned->brand_id,
                'grade_id' => $request->grade_id,
                'variant_id' => $scanned->variant_id,
                'glue_type_id' => $request->glue_type_id,
                'thickness_id' => $request->thickness_id,
                'quantity' => 1,
                'price' => $request->price,
                'manufacturing_date' => now(),
                'status' => $status
            ]);
    
            return response()->json(['message' => 'New Record added', 'status' => $status], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
