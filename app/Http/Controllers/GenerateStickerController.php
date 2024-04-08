<?php

namespace App\Http\Controllers;

use App\Models\BarcodeDetails;
use App\Models\GlueType;
use App\Models\Thickness;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateStickerController extends Controller
{
    public function generate(Request $request)
    {
        // Validate request data
        $request->validate([
            'brand' => 'required|string',
            'variant' => 'nullable|string',
            'quantity' => 'required|string',
        ]);

        $brandId = $request->brand_id;
        $variantId = $request->variant_id;
    
        $brand_name = Brand::where('id', $brandId)->pluck('brand_name')->first();

        // Calculate the starting index of the middle letters
        $middle_start = floor(strlen($brand_name) / 2) - 1;

        // Extract the 3 middle letters
        $letters = substr($brand_name, $middle_start, 3);

                
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
            $latestSeries = ($latestBatch->barcode_number)[2];
            $series = (int)$latestSeries + 1;
        }

        // Initialize a PHPWord object
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Create a single section for the Word document
        $section = $phpWord->addSection(['colsNum' => 2]); // Set columns to 2

        // Loop through each sticker to generate and attach barcode
        for ($i = 1; $i <= intval($request->quantity); $i++) {
            // Generate barcode number
            $formattedSeries = str_pad($series, 5, '0', STR_PAD_LEFT);
            $barcode_number = $currentYear . $currentMonth . $formattedSeries . $letters;

            // Create barcode details with the generated barcode number
            $details = BarcodeDetails::create([
                'brand_id' => $request->brand_id,
                'variant_id' => $request->variant_id,
                'thickness_id' => $request->thickness_id,
                'glue_type_id' => $request->glue_type_id,
                'barcode_number' => $barcode_number
            ]);

            // Increment the series number for the next iteration
            $series++;

            // Generate barcode image
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeImage = $generator->getBarcode($barcode_number, $generator::TYPE_CODE_128, 8, 550);

            // Load existing image
            if($request->variant != null){
                $existingImage = imagecreatefromjpeg(public_path('/templates/' . $request->input('brand') . '-' . $request->input('variant'). '.jpg'));
            }else{
                $existingImage = imagecreatefromjpeg(public_path('/templates/' . $request->input('brand'). '.jpg'));
            }

            // Load barcode image
            $barcodeImageResource = imagecreatefromstring($barcodeImage);

            // Get dimensions
            $barcodeWidth = imagesx($barcodeImageResource);
            $barcodeHeight = imagesy($barcodeImageResource);

            // Calculate position to attach barcode
            $x = 630; // Adjust as needed
            $y = 2790; // Adjust as needed

            // Specify the path to your font file
            $textFont = public_path('/fonts/impact.ttf');

            $thickness = Thickness::select('value')->where('id', $request->thickness_id)->get();
            $glue = GlueType::select('type')->where('id', $request->glue_type_id)->get();

            foreach($glue as $a){
                $glue_type = $a->type;
            }

            foreach($thickness as $b){
                $thickness_value = $b->value;
            }

            // Attach barcode to existing image
            imagecopy($existingImage, $barcodeImageResource, $x, $y, 0, 0, imagesx($barcodeImageResource), imagesy($barcodeImageResource));

            // Define text contents
            $text1 = "THICKNESS";
            $text2 = "TYPE";
            $text3 = $thickness_value . " MM";
            $text4 = "TYPE " . $glue_type;

            // Set font size for all texts
            $font_size = 80; // Adjust font size here

            // Set vertical spacing between texts
            $vertical_spacing = 20;

            // Add text below the barcode
            $textColor = imagecolorallocate($existingImage, 0, 0, 0); // Black color
            imagettftext($existingImage, $font_size, 0, 110, 2300 + $barcodeHeight + $vertical_spacing, $textColor, $textFont, $text1);
            imagettftext($existingImage, $font_size, 0, 2050, 2300 + $barcodeHeight + 2 * $vertical_spacing, $textColor, $textFont, $text2);
            imagettftext($existingImage, $font_size, 0, 180, 2750 + $barcodeHeight + 3 * $vertical_spacing, $textColor, $textFont, $text3);
            imagettftext($existingImage, $font_size, 0, 2045, 2750 + $barcodeHeight + 4 * $vertical_spacing, $textColor, $textFont, $text4);

            // Convert image resource to string
            ob_start();
            imagejpeg($existingImage);
            $imageContents = ob_get_clean();

            // Add the image with the barcode to the Word document
            $section->addImage($imageContents, array(
                'width' => 198.45, // 7cm converted to points (1 cm = 28.35 points)
                'height' => 288, // 10cm converted to points (1 cm = 28.35 points)
            ));

            // Clean up
            imagedestroy($barcodeImageResource);
            imagedestroy($existingImage);
        }

        // Save the Word document with a unique filename
        $wordDocsPath = public_path('/word-docs');
        if (!is_dir($wordDocsPath)) {
            mkdir($wordDocsPath, 0755, true);
        }

        $DATE = Carbon::now()->format('Y-m-d-h');
        $document = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $document->save($wordDocsPath . '/' . $request->brand . '-' . $DATE . '.docx');

        // Return success message
        return response("Barcode stickers and Word document generated successfully.", 200);
    }
}
