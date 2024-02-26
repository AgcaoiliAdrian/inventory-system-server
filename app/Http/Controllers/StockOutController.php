<?php

namespace App\Http\Controllers;
use App\Models\StockOut;

use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index(){
        try {
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function stockOut(Request $request){
        try {
            
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
