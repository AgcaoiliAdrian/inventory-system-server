<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlueType;
use App\Models\Brand;

class GlueTypeController extends Controller
{
    public function index(){
        try {
            
            $glue = GlueType::all();

            return response()->json($glue);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'brand' => 'required|string',
        ]);

        try {
            $glue = GlueType::firstOrCreate([
                'type' => $validatedData['type'],
                'brand' => $validatedData['brand']
            ]);
        
            if ($glue->wasRecentlyCreated) {
                return response()->json([
                    'message' => 'Glue successfully added.'
                ]);
            } else {
                return response()->json([
                    'message' => 'Glue already exists.'
                ]);
            }
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }      
    }

    public function update($id, Request $request){
        try {
            
            $glue = GlueType::find($id);
            // $glue -> brand_id = $request -> id;
            $glue -> type = $request -> type;
            $glue ->  brand = $request -> brand;
            $glue->save();

            return response()->json([
                'message' => 'Success',
                'data' => $glue
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
