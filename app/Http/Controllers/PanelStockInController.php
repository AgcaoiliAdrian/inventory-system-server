<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Panel;
use App\Models\BarcodeDetails;

class PanelStockInController extends Controller
{
    public function index(){
        try {
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
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
            $panel_stock -> manufacturing_date = Carbon::now();
            $panel_stock -> quantity = 1;
            $panel_stock -> status = 'in';
            $panel_stock -> save();

            return response('Success');

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
