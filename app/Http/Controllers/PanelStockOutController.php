<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempPanelOut;
use App\Models\Panel;
use App\Models\Crate;
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
    
            return response()->json($data, 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function tempPanelStockOut($id, Request $request) {
        try {
            // Check if a panel with the provided barcode_id exists
            $panel = Panel::where('barcode_id', $id)->first();
    
            if (!$panel) {
                // If no panel is found, check if a crate with the provided barcode_id exists
                $crate = Crate::where('barcode_id', $id)->first();
    
                if (!$crate) {
                    // If neither panel nor crate is found, return an error response
                    return response()->json([
                        'message' => 'Neither panel nor crate found with the provided barcode ID'
                    ], 404);
                }
    
                // If a crate is found, create a new TempPanelOut record with crate_stock_id
                $temp_out = new TempPanelOut();
                $temp_out->crate_stock_id = $crate->id;
                $temp_out->save();
    
                return response()->json([
                    'message' => 'Crate stock out recorded successfully'
                ], 200);
    
            } else {
                // If a panel is found, check if it's already recorded in TempPanelOut
                $existingPanel = TempPanelOut::where('panel_stock_id', $panel->id)->first();
    
                if ($existingPanel) {
                    // If the panel is already recorded, return an error response
                    return response()->json([
                        'message' => 'Panel already exists in temporary panel out records'
                    ], 400);
                }
    
                // If the panel is not recorded, create a new TempPanelOut record with panel_stock_id
                $temp_out = new TempPanelOut();
                $temp_out->panel_stock_id = $panel->id;
                $temp_out->save();
    
                return response()->json([
                    'message' => 'Panel stock out recorded successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the process
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }    

    public function savePanelStockOut(){
        try {
            $temp_panel_out = TempPanelOut::pluck('panel_stock_id')->toArray();
            $temp_crate_out = TempPanelOut::pluck('crate_stock_id')->toArray();

            Panel::whereIn('id', $temp_panel_out)
                ->update(['status' => 'out']);

            Crate::whereIn('id', $temp_crate_out)
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

    public function delete($id){
        try {
            $brand = TempPanelOut::find($id)->delete();

            return response('Success', 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}