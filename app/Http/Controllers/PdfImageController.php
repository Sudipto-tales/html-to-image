<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;

class PdfImageController extends Controller
{
    public function generateFiles(Request $request)
    {
        // Check for API key authentication
        $apiKey = env('API_ACCESS_KEY');
        $providedKey = $request->header('X-API-KEY');
        if (!$providedKey) {
            return response()->json(['message' => 'No API Key provided'], 400);
        }

        if (!$apiKey || trim($providedKey) !== trim($apiKey)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        // Validate input fields
        $request->validate([
            'html' => 'required|string',
            'format' => 'nullable|string|in:A4,A3,Letter,Legal',
            'orientation' => 'nullable|string|in:portrait,landscape',
            'margin' => 'nullable|integer|min:0',
            'width' => 'nullable|integer|min:100',
            'height' => 'nullable|integer|min:100',
        ]);

        $html = $request->input('html');
        $format = $request->input('format', 'A4');
        $orientation = $request->input('orientation', 'portrait');
        $margin = $request->input('margin', 10);
        $width = $request->input('width', 1200);
        $height = $request->input('height', 700);
        $hash = md5($html . $format . $orientation . $margin . $width . $height); // Unique hash based on input

        // File names based on hash
        $pdfFileName = "generated_{$hash}.pdf";
        $imageFileName = "generated_{$hash}.png";
        $pdfFilePath = storage_path("app/public/{$pdfFileName}");
        $imageFilePath = storage_path("app/public/{$imageFileName}");

        // Check if files already exist
        if (Storage::disk('public')->exists($pdfFileName) && Storage::disk('public')->exists($imageFileName)) {
            return response()->json([
                'message' => 'Files already exist',
                'pdf_url' => secure_asset("storage/{$pdfFileName}"),
                'image_url' => secure_asset("storage/{$imageFileName}")
            ]);
        }

        // Generate PDF if it doesn't exist
        if (!Storage::disk('public')->exists($pdfFileName)) {
            Browsershot::html($html)
                ->showBackground()
                ->format($format)
                ->landscape($orientation === 'landscape')
                ->margins($margin, $margin, $margin, $margin)
                ->savePdf($pdfFilePath);
        }

        // Generate Image if it doesn't exist
        if (!Storage::disk('public')->exists($imageFileName)) {
            Browsershot::html($html)
                ->showBackground()
                ->windowSize($width, $height)
                ->deviceScaleFactor(2)
                ->save($imageFilePath);
        }

        // Return response with file URLs
        return response()->json([
            'message' => 'Files generated successfully',
            'pdf_url' => asset("storage/{$pdfFileName}"),
            'image_url' => asset("storage/{$imageFileName}")
        ]);
    }
}
