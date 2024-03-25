<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        // $barcode_number = Helpers::generateBarcodeNumber();

        $stored_ids = []; // Array to store the IDs of stored BarcodeDetails

        for ($i = 1; $i <= intval($request->quantity); $i++) {

            // Generate a new barcode number for each iteration
            $barcode_number = Helpers::generateBarcodeNumber($brandId);

            $barcode = BarcodeDetails::create([
                'brand_id' => $request->brand_id,
                'variant_id' => $request->variant_id,
                'barcode_number' => $barcode_number
            ]);

            // Store the ID of the created BarcodeDetails
            $stored_ids[] = $barcode->id;
        }

        // Get the image filename from the request
        $imageFilename = $request->input('brand');

        // Construct the full path to the image based on the filename
        $existingImagePath = public_path('/templates/' . $imageFilename . '.jpg'); // Append the jpg extension

        // Check if the image file exists
        if (!file_exists($existingImagePath)) {
            return response()->json(['error' => 'Image file not found.'], 404);
        }

        // Generate barcode
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

        // Initialize an array to store barcode images
        $barcodeImages = [];

        foreach ($stored_ids as $stored_id) {
            // Get barcode image for each stored ID
            $foregroundColor = ['r' => 0, 'g' => 0, 'b' => 0];
            $barcodeImages[] = $generator->getBarcode($stored_id, $generator::TYPE_CODE_128, 18, 600);
        }

        // Initialize an array to store paths of generated barcode images
        $barcodeImagePaths = [];

        foreach ($barcodeImages as $barcodeImage) {
            // Load existing image
            $existingImage = imagecreatefromjpeg($existingImagePath);

            // Load barcode image
            $barcodeImageResource = imagecreatefromstring($barcodeImage);

            // Get dimensions
            $barcodeWidth = 2500;
            $barcodeHeight = imagesy($barcodeImageResource);

            // Calculate position to attach barcode
            $x = 800; // Adjust as needed
            $y = 2730; // Adjust as needed

            // Attach barcode to existing image
            imagecopy($existingImage, $barcodeImageResource, $x, $y, 0, 0, $barcodeWidth, $barcodeHeight);

            // Clean up
            imagedestroy($barcodeImageResource);

            // Save the image with the barcode
            $imagePathWithBarcode = tempnam(sys_get_temp_dir(), 'barcode_with_');
            imagejpeg($existingImage, $imagePathWithBarcode);

            // Add path of generated barcode image to the array
            $barcodeImagePaths[] = $imagePathWithBarcode;

            // Clean up
            imagedestroy($existingImage);
        }

        // Initialize a PHPWord object
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Create a single section for the Word document
        $section = $phpWord->addSection(['colsNum' => 2]); // Set columns to 2

        // Loop to add images to the section
        foreach ($barcodeImagePaths as $imagePathWithBarcode) {
            // Add the image with the barcode to the Word document
            $section->addImage($imagePathWithBarcode, array(
                'width' => 210, // 7cm converted to points (1 cm = 28.35 points)
                'height' => 300, // 10cm converted to points (1 cm = 28.35 points)
            ));
        }

        // Save the Word document with a unique filename
        // $wordDocsPath = public_path('/word-docs');
        // if (!is_dir($wordDocsPath)) {
        //     mkdir($wordDocsPath, 0755, true);
        // }

        $DATE = Carbon::now()->format('Y-m-d-h');
        $fileName = $request->brand . '-' . $DATE . '.docx';
        $filePath = tempnam(sys_get_temp_dir(), 'word_doc_');
        $document = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $document->save($filePath);

        // Clean up generated barcode images
        foreach ($barcodeImagePaths as $imagePathWithBarcode) {
            unlink($imagePathWithBarcode);
        }

        // Return success message
        return Response::download($filePath, $fileName)->deleteFileAfterSend(false);
    }

    private function createBarcodeDetails(Request $request, $barcode_number)
    {
        return BarcodeDetails::create([
            'brand_id' => $request->brand_id,
            'variant_id' => $request->variant_id,
            'barcode_number' => $barcode_number
        ]);
    }
}
    