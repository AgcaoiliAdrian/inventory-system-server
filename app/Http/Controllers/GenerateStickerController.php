<?php

namespace App\Http\Controllers;
use App\Models\BarcodeDetails;
use App\Helpers\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateStickerController extends Controller
{
    public function generate(Request $request)
    {
        // Validate request data
        $request->validate([
            'brand' => 'required|string',
            'quantity' => 'required|string',
        ]);

        $brandId = $request->brand_id;
        
        $currentYear = date('Y');
        $currentMonth = date('m');

        $latestBatch = BarcodeDetails::where('brand_id', $request->brand_id)
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

        // Generate and assign barcode numbers within the loop
        for ($i = 1; $i <= intval($request->quantity); $i++) {
            // Format the series number with leading zeros
            $formattedSeries = str_pad($series, 4, '0', STR_PAD_LEFT);

            // Concatenate the components to form the barcode number
            $barcode_number = $currentYear . '-' . $currentMonth . '-' . $formattedSeries;

            // Create barcode details with the generated barcode number
            $details = BarcodeDetails::create([
                'brand_id' => $request->brand_id,
                'variant_id' => $request->variant_id,
                'barcode_number' => $barcode_number
            ]);

            // Increment the series number for the next iteration
            $series++;

            // Generate barcode
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeImage = $generator->getBarcode($details->id, $generator::TYPE_CODE_128, 18, 600);
        }       
    
        // Get the image filename from the request
        $imageFilename = $request->input('brand');
    
        // Construct the full path to the image based on the filename
        $existingImagePath = public_path('/templates/' . $imageFilename . '.jpg'); // Append the jpg extension
    
        // Check if the image file exists
        if (!file_exists($existingImagePath)) {
            return response()->json(['error' => 'Image file not found.'], 404);
        }
    
        // Load existing image
        $existingImage = imagecreatefromjpeg($existingImagePath);
    
        // Load barcode image
        $barcodeImageResource = imagecreatefromstring($barcodeImage);
    
        // Get dimensions
        $barcodeWidth = 2500;
        $barcodeHeight = imagesy($barcodeImageResource);
    
        // Calculate position to attach barcode
        $x = 80; // Adjust as needed
        $y = 2730; // Adjust as needed
    
        // Attach barcode to existing image
        imagecopy($existingImage, $barcodeImageResource, $x, $y, 0, 0, $barcodeWidth, $barcodeHeight);
    
        // Clean up
        imagedestroy($barcodeImageResource);
    
        // Initialize a PHPWord object
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
    
        // Create a single section for the Word document
        $section = $phpWord->addSection(['colsNum' => 2]); // Set columns to 2
    
        // Save the image with the barcode
        $imagePathWithBarcode = tempnam(sys_get_temp_dir(), 'barcode_with_');
        imagejpeg($existingImage, $imagePathWithBarcode);
    
        // Loop to add images to the section
        for ($i = 1; $i <= intval($request->quantity); $i++) {
            // Add the image with the barcode to the Word document
            $section->addImage($imagePathWithBarcode, array(
                'width' => 210, // 7cm converted to points (1 cm = 28.35 points)
                'height' => 300, // 10cm converted to points (1 cm = 28.35 points)
            ));
        }
    
        // Save the Word document with a unique filename
        $wordDocsPath = public_path('/word-docs');
        if (!is_dir($wordDocsPath)) {
            mkdir($wordDocsPath, 0755, true);
        }

        $DATE = Carbon::now()->format('Y-m-d-h');
        $document = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $document->save($wordDocsPath . '/'.$request->brand.'-'. $DATE.'.docx');
    
        // Clean up
        imagedestroy($existingImage);
        unlink($imagePathWithBarcode);
    
        // Return success message
        return "Barcode and Word document with images generated successfully.";
    }
}