<?php

namespace App\Http\Controllers;
use App\Models\Supplier;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(){
        try {
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request){
        try {
            
            $supplier = new Supplier();
            $supplier -> supplier_name = $request -> supplier_name;
            $supplier -> category = $request -> category;
            $supplier -> contact_number = $request -> contact_number;
            $supplier -> contact_person = $request -> contact_person;
            $supplier -> save();

            return response ('Success', 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
