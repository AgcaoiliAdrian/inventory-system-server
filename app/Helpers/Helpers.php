<?php

namespace App\Helpers;
use App\Models\CrateStock;
use Illuminate\Support\Facades\DB;

class Helpers
{
    public static function generateBatchNumber($encodingType)
    {
        if ($encodingType === 1) {
            // Generate batch number using the current date
            return date('Y-m') . '-0001'; // Assuming series starts from 0001
        }

        $currentYear = date('Y');
        $currentMonth = date('m');

        // Get the latest batch number for the current year and month
        $latestBatch = DB::table('crate_stock')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('created_at', 'desc')
            ->first();

        $series = 1;
        if ($latestBatch) {
            // Extract the series number from the latest batch number and increment it
            $latestSeries = explode('-', $latestBatch->batch_number)[2];
            $series = (int)$latestSeries + 1;
        }

        // Format the series number with leading zeros
        $formattedSeries = str_pad($series, 4, '0', STR_PAD_LEFT);

        // Concatenate the components to form the batch number
        $batchNumber = $currentYear . '-' . $currentMonth . '-' . $formattedSeries;

        return $batchNumber;
    }

    public static function generateBarcodeNumber($brandId){
        $currentYear = date('Y');
        $currentMonth = date('m');
    
        // Get the latest batch number for the current year and month
        $latestBatch = DB::table('barcode_details')
            ->where('brand_id', $brandId)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('created_at', 'desc')
            ->orderBy('barcode_number', 'desc')
            ->first();
    
        $series = 1;
        if ($latestBatch) {
            // Extract the series number from the latest batch number and increment it
            $latestSeries = explode('-', $latestBatch->barcode_number)[2];
            $series = (int)$latestSeries + 1;
        }
    
        // Format the series number with leading zeros
        $formattedSeries = str_pad($series, 4, '0', STR_PAD_LEFT);
    
        // Concatenate the components to form the batch number
        $barcodeNumber = $currentYear . '-' . $currentMonth . '-' . $formattedSeries;
    
        return $barcodeNumber;
    }
}