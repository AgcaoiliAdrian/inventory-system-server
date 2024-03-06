<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Variant;

class BrandController extends Controller
{
    public function index(){
        try {
            $brands = Brand::with('variant')->get();
    
            return response()->json($brands);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand_name' => 'required|string',
            'variant_name' => 'nullable|array',
            'variant_name.*' => 'string',
        ]);
    
        try {
            $brand = Brand::firstOrCreate(['brand_name' => $validatedData['brand_name']]);
    
            if ($validatedData['variant_name']) {
                $variants = [];
                foreach ($validatedData['variant_name'] as $variant) {
                    $variants[] = new Variant(['variant_name' => $variant]);
                }
                $brand->variant()->saveMany($variants);
            }
    
            return response()->json([
                'message' => 'Brand and variants successfully added.',
                'data' => $brand
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }

    public function update($id, Request $request){
        try {
            $brand = Brand::find($id);
            $brand -> brand_name = $request -> brand_name;
            $brand -> updated_at = now();
            $brand -> save();

            return response()->json([
                'message' => 'Success',
                'data' => $brand
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
    
}
