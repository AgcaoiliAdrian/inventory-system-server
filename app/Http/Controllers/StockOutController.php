<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\StockOut;

use Illuminate\Http\Request;
use App\Models\Panel;
use App\Models\BarcodeDetails;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index(){
        try {
            $stockOuts = StockOut::with([
                'panel.barcodeDetails.brand',
                'panel.barcodeDetails.variant',
                'panel.grade',
                'panel.barcodeDetails.thickness'
            ])->get();
            
            // Fetching the sum of quantities by brand
            $quantitiesByBrand = DB::table('barcode_details')
                ->select('barcode_details.brand_id', DB::raw('SUM(panel_stock.quantity) as total_quantity'))
                ->join('panel_stock', 'barcode_details.id', '=', 'panel_stock.barcode_id')
                ->groupBy('barcode_details.brand_id')
                ->get();
            
            // Associating the sum of quantities with the respective brands in each StockOut
            foreach ($stockOuts as $stockOut) {
                $barcodeDetails = $stockOut->panel->barcodeDetails;
            
                // Find the corresponding sum of quantities for the brand of this BarcodeDetails
                $sumQuantity = $quantitiesByBrand->firstWhere('brand_id', $barcodeDetails->brand_id);
            
                // Add the total_quantity attribute to the brand relationship
                $barcodeDetails->brand->total_quantity = $sumQuantity ? $sumQuantity->total_quantity : 0;
            }
            
            return response()->json($stockOuts);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function stockOut($id, Request $request){
        try {
            $panel = Panel::where('barcode_id', $id)->firstOrFail();
            $panel_id = $panel->id;
    
            // Check if StockOut record already exists for the given panel_stock_id
            $existingStockOut = StockOut::where('panel_stock_id', $panel_id)->first();
    
            if (!$existingStockOut) {
                // Create a new StockOut record if it doesn't exist
                $out = new StockOut();
                $out->panel_stock_id = $panel_id;
                $out->is_batch = $request->encoding_type;
                $out->stock_out_date = Carbon::now();
                $out->save();
    
                return response('Success');
            } else {
                return response('Stock out record already exists');
            }
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }
    
}
