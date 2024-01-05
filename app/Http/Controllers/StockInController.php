<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Panel;

class StockInController extends Controller
{
    public function show($id, Request $request){
        try {
            $details = Brand::with('glue', 'thickness', 'variant', 'grade')->find($id);

            return response()->json($details);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th
            ]);
        }
    }

    public function store(Request $request){
        try {
            $brand_id = $request->brand_id;
            $glue_id = $request->glue_id;
            $grade_id = $request->grade_id;
            $thickness_id = $request->thickness_id;
            $variant_id = $request->variant_id;

            $product_id = Product::where('brand_id', $brand_id)
                                ->where('thickness_id', $thickness_id)
                                ->where('variant_id', $variant_id)
                                ->where('glue_type_id', $glue_id)
                                ->first();

            if($product_id){
                $store = Panel::create([
                    'product_id' => $product_id -> id,
                    // 'grade_id' => 
                    'quantity' => 1,
                    'manufacturing_date' => $request -> date,
                    'is_batch' => $request -> status == "Batch" ? 0 : 1
                ]);
            
                return response() -> json([
                    'message' => 'Success'
                ]); 
            
            }else{
                return response('Product Not Found');
            }
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
