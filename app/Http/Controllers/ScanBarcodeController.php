<?php

namespace App\Http\Controllers;
use App\Models\BarcodeDetails;

use Illuminate\Http\Request;

class ScanBarcodeController extends Controller
{
    public function show($id, Request $request){
        try {
            $scanned = BarcodeDetails::with(['variant', 'brand'])->find($id);
            
            return response()->json($scanned);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
