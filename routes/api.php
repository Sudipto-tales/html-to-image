<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfImageController;

Route::post('/generate-file', [PdfImageController::class, 'generateFiles']);
