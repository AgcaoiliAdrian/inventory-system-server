<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempPanelOut;
use App\Models\Panel;
use App\Models\BarcodeDetails;

class PanelStockOutController extends Controller
{

    public function index(Request $request){
        try {
            $data = BarcodeDetails::with(['variant', 'brand', 'thickness', 'grade'])
            ->join('panel_stock', 'barcode_details.id', '=', 'panel_stock.barcode_id')
            ->whereIn('barcode_details.id', function ($query) {
                $query->select('barcode_id')->from('panel_stock');
            })
            ->where('panel_stock.status', 'out')
            ->get();        
    
            return response()->json($data);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function tempPanelStockOut($id, Request $request){
        try {

        $panel = Panel::where('barcode_id', $id)->pluck('id')->first();
        $existingPanel = TempPanelOut::where('panel_stock_id', $panel)->first();

        if ($existingPanel) {
            return response()->json([
                'message' => 'Panel already exist'
            ], 400);
        }

        $temp_out = new TempPanelOut();
        $temp_out -> panel_stock_id = $panel;
        $temp_out -> save();

        return response('Success');

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function savePanelStockOut(){
        try {
            $temp_panel_out = TempPanelOut::pluck('panel_stock_id')->toArray();

            // Update all Crates with matching batch numbers
            Panel::whereIn('id ', $temp_panel_out)
                ->update(['status' => 'out']);

            // Truncate the TempPanelOut table
            TempPanelOut::truncate();

            return response('Success');

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}


