<?php

namespace App\Http\Controllers;
use App\Models\BarcodeDetails;
use App\Models\User;

use Illuminate\Http\Request;

class ScanBarcodeController extends Controller
{
    public function show($barcode, Request $request)
    {
        try {
            $scanned = BarcodeDetails::with(['variant', 'brand', 'thickness', 'glue'])->where('barcode_number', $barcode)->get();

            $graders = User::with(['info'])
                ->whereHas('info', function($query) {
                    $query->where('system_role', 'Grader');
                })
                ->get();

            if ($scanned->isEmpty()) {
                return response()->json(['message' => 'Barcode scanned is invalid'], 401);
            }

            // Merge $scanned and $graders
            $mergedResult = [
                'details' => $scanned,
                'graders' => $graders
            ];

            return response()->json($mergedResult, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }
}
