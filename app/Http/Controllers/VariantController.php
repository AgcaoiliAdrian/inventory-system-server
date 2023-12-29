<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variant;

class VariantController extends Controller
{
    public function update($id, Request $request){
        try {
            
            $variant = Variant::find($id);
            $variant -> brand_id = $request -> id;
            $variant -> variant_name = $request -> variant_name;
            $variant -> save();

            return response()->json([
                'message' => 'Success'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
