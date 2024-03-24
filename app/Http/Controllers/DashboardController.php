<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Crate;
use App\Models\Panel;
use App\Models\BarcodeDetails;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stocksData(Request $request){
        try {
            $startOfWeek = Carbon::now()->startOfWeek();

            $query = BarcodeDetails::query();

            // Filter by brand name if provided
            if ($request->has('brand_name')) {
                $query->whereHas('brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                });
            }

            $crateCount = Crate::count();
            $panelCount = Panel::count();
            $totalStickers = $query->count();
            $totalStickerUsed = $crateCount + $panelCount;

            // Filter crate and panel counts by brand name if provided
            if ($request->has('brand_name')) {
                $crateCount = Crate::whereHas('barcodeDetails.brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                })->count();

                $panelCount = Panel::whereHas('barcodeDetails.brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                })->count();

                // Recalculate total sticker used if brand filter applied
                $totalStickerUsed = $crateCount + $panelCount;
            }

            // Filter sticker used weekly by brand name if provided
            $stickerUsedWeekly = Crate::where('created_at', '>=', $startOfWeek);
            $stickerUsedWeekly = $query->whereHas('crateStock', function ($q) use ($startOfWeek) {
                $q->where('created_at', '>=', $startOfWeek);
            })->count() + $panelCount;

            $results = (object) [
                'crate_total' => $crateCount,
                'panel_total' => $panelCount,
                'total_stickers' => $totalStickers,
                'total_sticker_used' => $totalStickerUsed,
                'sticker_used_weekly' => $stickerUsedWeekly,
            ];

            return response()->json($results, 200);            

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function topSelling(Request $request){
        try {
            $data = BarcodeDetails::with('brand', 'variant', 'glue', 'thickness', 'grade')
                ->whereHas('glue', function ($query) {
                    $query->whereNotNull('glue_type_id');
                })
                ->whereHas('thickness', function ($query) {
                    $query->whereNotNull('thickness_id');
                })
                ->leftJoin('crate_stock', function($join) {
                    $join->on('barcode_details.id', '=', 'crate_stock.barcode_id')
                        ->where('crate_stock.status', '=', 'out');
                })
                ->leftJoin('panel_stock', function($join) {
                    $join->on('barcode_details.id', '=', 'panel_stock.barcode_id')
                        ->where('panel_stock.status', '=', 'out');
                })
                ->leftJoin('crate_stock as cs_in', function($join) {
                    $join->on('barcode_details.id', '=', 'cs_in.barcode_id')
                        ->where('cs_in.status', '=', 'in');
                })
                ->leftJoin('panel_stock as ps_in', function($join) {
                    $join->on('barcode_details.id', '=', 'ps_in.barcode_id')
                        ->where('ps_in.status', '=', 'in');
                })
                ->select(
                    'barcode_details.brand_id',
                    'barcode_details.variant_id',
                    'barcode_details.glue_type_id',
                    'barcode_details.thickness_id',
                    'barcode_details.grade_id',
                    DB::raw('COUNT(DISTINCT crate_stock.id) + COUNT(DISTINCT panel_stock.id) AS item_sold'),
                    DB::raw('COUNT(DISTINCT cs_in.id) + COUNT(DISTINCT ps_in.id) AS item_left'),
                    DB::raw('COALESCE(SUM(crate_stock.price), 0) + COALESCE(SUM(panel_stock.price), 0) AS gross_sale')
                )
                ->groupBy(
                    'barcode_details.brand_id',
                    'barcode_details.variant_id',
                    'barcode_details.glue_type_id',
                    'barcode_details.thickness_id',
                    'barcode_details.grade_id',
                )
                ->get();
    
            return $data;
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }  
    
    public function revenue(){
        try {
            // Initialize an array to store gross sales for each month
            $monthlySales = [];
    
            // Get the current year
            $currentYear = date('Y');
    
            // Loop through each month of the year
            for ($month = 1; $month <= 12; $month++) {
                // Format month as two digits (e.g., 01, 02, ..., 12)
                $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
    
                // Concatenate year and month to form YYYY-MM format
                $currentMonth = $currentYear . '-' . $formattedMonth;
    
                // Retrieve sales from panel_stock for the current month
                $panelSales = Panel::where('status', 'out')
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                    ->sum('price');
    
                // Retrieve sales from crate_stock for the current month
                $crateSales = Crate::where('status', 'out')
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                    ->sum('price');
    
                // Calculate total gross sales for the current month
                $monthlyGrossSales = $panelSales + $crateSales;
    
                // Store the total gross sales for the current month in the array
                $monthlySales[$currentMonth] = $monthlyGrossSales;
            }
    
            // Return the gross sales for all months as JSON response
            return response()->json([
                'monthly_gross_sales' => $monthlySales
            ], 200);
        } catch (\Throwable $th) {
            // Handle any exceptions that might occur
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    
    public function stickerUsage(Request $request){
        try {

            $query = BarcodeDetails::query();

            if ($request->has('brand_name')) {
                $query->whereHas('brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                });
            }

            $crateOut = Crate::count();
            $panelOut = Panel::count();

            $crateIn = Crate::count();
            $panelIn = Panel::count();

            $stickers =  $query->count();;

            if ($request->has('brand_name')) {
                $crateOut = Crate::whereHas('barcodeDetails.brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                })->where('status', 'out')->count();
            
                $panelOut = Panel::whereHas('barcodeDetails.brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                })->where('status', 'out')->count();
            
                $crateIn = Crate::whereHas('barcodeDetails.brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                })->where('status', 'in')->count();
            
                $panelIn = Panel::whereHas('barcodeDetails.brand', function ($q) use ($request) {
                    $q->where('brand_name', $request->input('brand_name'));
                })->where('status', 'in')->count();
            
                $total_out = $crateOut + $panelOut;
                $total_in = $panelIn + $crateIn;
            }            

            return response()->json([
                'sticker_encoded' => $total_in,
                'sticker_out' => $total_out,
                'generated_sticker' => $stickers
            ]);


        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}