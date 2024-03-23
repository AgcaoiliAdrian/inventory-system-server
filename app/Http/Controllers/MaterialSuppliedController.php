<?php

namespace App\Http\Controllers;
use App\Models\GlueSupplied;
use App\Models\PlywoodSupplied;
use App\Models\Supplier;

use Illuminate\Http\Request;

class MaterialSuppliedController extends Controller
{
    public function index()
    {
        try {
            $suppliers = Supplier::with('plywoodSupplied', 'glueSupplied')->get();
    
            return response()->json(['data' => $suppliers], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'supplier_name' => 'required|string',
                'category' => 'required|string',
                'contact_number' => 'required|string',
                'contact_person' => 'required|string',
                'plywood_type' => 'required|array',
                'plywood_brand' => 'required|array',
                'glue_type' => 'required|array',
                'glue_brand' => 'required|array',
            ]);
        
            $supplier = new Supplier();
            $supplier->supplier_name = $validatedData['supplier_name'];
            $supplier->category = $validatedData['category'];
            $supplier->contact_number = $validatedData['contact_number'];
            $supplier->contact_person = $validatedData['contact_person'];
            $supplier->save();
        
            // Loop through plywood types and brands and save them
            foreach ($validatedData['plywood_type'] as $index => $plywoodType) {
                $plywoodBrand = $validatedData['plywood_brand'][$index];
                
                $plywoodSupplied = new PlywoodSupplied(); // Create a new instance for each iteration
                $plywoodSupplied->supplier_id = $supplier->id;
                $plywoodSupplied->plywood_type = $plywoodType;
                $plywoodSupplied->plywood_brand = $plywoodBrand;
                $plywoodSupplied->save();
            }

            foreach ($validatedData['glue_type'] as $index => $glueType){
                $glueBrand = $validatedData['glue_brand'][$index];

                $glueSupplied = new GlueSupplied();
                $glueSupplied->supplier_id = $supplier->id;
                $glueSupplied->glue_type = $glueType;
                $glueSupplied->glue_brand = $glueBrand;
                $glueSupplied->save();
            }
        
            return response()->json([
                'message' => 'Success',
                'supplier' => $supplier,
                'plywood' => $plywoodSupplied,
                'glue' => $glueSupplied
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }   
}