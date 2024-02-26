<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\GlueType;
use App\Models\Thickness;
use App\Models\Variant;
use App\Models\Panel;
use App\Models\BarcodeDetails;

class ProductController extends Controller
{
    public function index(){
        try {
            $products = Product::with('brand', 'glue', 'thickness', 'variant')->get();
    
            $products->each(function ($product) {
                $totalQuantity = 0;

                // Retrieve all BarcodeDetails for the product
                $barcodeDetails = BarcodeDetails::where([
                    'brand_id' => $product->brand_id,
                    'variant_id' => $product->variant_id,
                    'glue_type_id' => $product->glue_type_id,
                    'thickness_id' => $product->thickness_id,
                ])->get();

                // Sum up quantities from panel_stock for each BarcodeDetails
                foreach ($barcodeDetails as $barcodeDetail) {
                    $totalQuantity += Panel::where('barcode_id', $barcodeDetail->id)->sum('quantity');
                }

                $product->stocks = $totalQuantity;
            });
    
            return response()->json($products);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }   

    public function store(Request $request){
        $validatedData = $request->validate([
            'manufacturing_date' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            // 'brand_id' => 'required',
            'glue_type_id' => 'required',
            'thickness_id' => 'required',
            'variant_id' => 'nullable'
        ]);
    
        try {
            // $brand = Brand::findOrFail($validatedData['brand_id']);
            $glue = GlueType::findOrFail($validatedData['glue_type_id']);
            $thickness = Thickness::findOrFail($validatedData['thickness_id']);
            $variant = $validatedData['variant_id'] ? Variant::findOrFail($validatedData['variant_id']) : null;

    
            $product = Product::create([
                // 'brand_id' => $brand->id,
                'thickness_id' => $thickness->id,
                'glue_type_id' => $glue->id,
                'variant_id' => $variant ? $variant->id : null,
                'description' => $request->description,
                'price' => $request->price,
            ]);
    
            return response()->json([
                'message' => 'Product Successfully Created',
            ]);
    
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }    

    public function show($id, Request $request){
        try {
            
            $product  = Brand::with('variant', 'grade', 'glue', 'thickness')->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json($product);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
    
    public function update(Request $request, $id){
    
        try {
            $product = Product::findOrFail($id);

            if ($product) {
                $product->update([
                    // 'brand_id' => $request->id,
                    'glue_type_id' => $request->id,
                    'thickness_id' => $request->id,
                    'variant_id' => $request->id,
                    'manufacturing_date' => $request -> manufacturing_date,
                    'description' => $request -> description,
                    'price' => $request -> price
                ]);
                $product -> updated_at = now();
            }
    
            return response()->json([
                'message' => 'Data Successfully Updated'
            ]);
    
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    
}
