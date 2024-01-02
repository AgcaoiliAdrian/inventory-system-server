<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Variant;

class ModelController extends Controller
{
    public function index(){
        try {
            $model = Models::with('brand','variant')->get();

            return response()->json($model);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand_name' => 'required|string',
            'variant_name' => 'nullable|string',
        ]);
    
        try {
            DB::beginTransaction();
    
            $brand = Brand::firstOrCreate(['brand_name' => $validatedData['brand_name']]);
    
            if ($validatedData['variant_name']) {
                $variant = Variant::firstOrCreate(['variant_name' => $validatedData['variant_name']]);
            }
    
            if ($brand->wasRecentlyCreated) {
                $successModel = Models::create([
                    'brand_id' => $brand->id,
                    'variant_id' => isset($variant) ? $variant->id : null,
                ]);
    
                DB::commit();
    
                return response()->json([
                    'message' => 'Brand successfully added.',
                    'success_model_id' => $successModel->id,
                ], 201);
            } else {
    
                return response()->json([
                    'message' => 'Brand already exists.'
                ]);
            }
    
        } catch (\Throwable $th) {
    
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
