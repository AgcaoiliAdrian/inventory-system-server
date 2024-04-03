<?php

namespace App\Http\Controllers;
use App\Models\BarcodeDetails;

use Illuminate\Http\Request;

class ScanBarcodeController extends Controller
{
    public function show($id, Request $request){
        try {
            $scanned = BarcodeDetails::with(['variant', 'brand', 'thickness', 'glue'])->find($id);
            

            if (!$scanned) return response()->json(['message' => 'Barcode scanned is invalid'], 401);

            return  response()->json($scanned, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }
}
