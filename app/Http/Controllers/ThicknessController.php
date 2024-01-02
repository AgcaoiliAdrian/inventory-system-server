<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thickness;
use App\Models\Brand;

class ThicknessController extends Controller
{
    public function index(){
        try {
            
            $thickness = Thickness::all();

            return response()->json($thickness);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'value' => 'required|string',
            'unit' => 'required|string',
            'brand_id' => 'required'
        ]);

        $brand = Brand::findOrFail($validatedData['brand_id']);

        try {
            $thickness = Thickness::firstOrCreate([
                'brand_id' => $brand -> id,
                'value' => $validatedData['value'],
                'unit' => $validatedData['unit']
            ]);
        
            if ($thickness->wasRecentlyCreated) {
                return response()->json([
                    'message' => 'Thickness successfully added.'
                ]);
            } else {
                return response()->json([
                    'message' => 'Thickness already exists.'
                ]);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function update($id, Request $request){
        try {
            
            $thickness = Thickness::find($id);
            $thickness -> brand_id = $request -> id;
            $thickness -> value = $request -> value;
            $thickness -> unit = $request -> unit;
            $thickness -> save();

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