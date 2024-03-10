<?php

namespace App\Http\Controllers;
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

    public function stockOut(Request $request){
        try {
            
            $out = new StockOut();
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
