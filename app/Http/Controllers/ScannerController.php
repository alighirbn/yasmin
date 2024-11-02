<?php

namespace App\Http\Controllers;

use App\Services\WiaScanner;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    private $scanner;

    public function __construct(WiaScanner $scanner)
    {
        $this->scanner = $scanner;
    }

    public function index()
    {
        $devices = $this->scanner->listDevices();
        return view('scanner', compact('devices'));
    }

    public function scan(Request $request)
    {
        $deviceId = $request->input('device_id');
        $this->scanner->connect($deviceId);

        $outputPath = storage_path('app/public/scans/') . uniqid() . '.png';
        $scannedImagePath = $this->scanner->scan($outputPath);

        return response()->json(['image_path' => asset('storage/scans/' . basename($scannedImagePath))]);
    }
}
