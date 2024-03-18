<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tempPanelStockOut;
use App\Models\Panel;

class PanelStockOutController extends Controller
{
    public function tempPanelStockOut($id, Request $request){
        try {

        $panel = Panel::where('barcode_id')->pluck('id')->first();
        $existingPanel = tempPanelStockOut::where('panel_stock_id', $panel)->first();

        if ($existingPanel) {
            return response()->json([
                'message' => 'Panel already exist'
            ], 400);
        }

        $temp_out = new tempPanelStockOut();
        $temp_out -> panel_stock_id = $panel;
        $temp_out -> save();

        if($temp_out){
            tempPanelStockOut::truncate();
        }

        return response('Success');

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }


}


