<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\GlueType;
use App\Models\Thickness;
use App\Models\Variant;

class ProductController extends Controller
{
    public function index(){
        try {
            $product = Product::with('brand', 'glue', 'thickness')->get();

            return response()->json($product);

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
            'brand_id' => 'required',
            'glue_type_id' => 'required',
            'thickness_id' => 'required',
            'variant_id' => 'nullable'
        ]);
    
        try {
            $brand = Brand::findOrFail($validatedData['brand_id']);
            $glue = GlueType::findOrFail($validatedData['glue_type_id']);
            $thickness = Thickness::findOrFail($validatedData['thickness_id']);
            $variant = Variant::findOrFail($validatedData['variant_id']);

    
            $product = Product::create([
                'brand_id' => $brand->id,
                'thickness_id' => $thickness->id,
                'glue_type_id' => $glue->id,
                'variant_id' => $variant->id,
                'manufacturing_date' => $request->manufacturing_date,
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
            
            $product  = Product::findorFail($id);

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
                    'brand_id' => $request->id,
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
