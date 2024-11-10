<?php

namespace App\Services;

use COM;
use Exception;
use GDText\Box;
use GDText\Color;

class WiaScanner
{
    private $deviceManager;
    private $device;

    public function __construct()
    {
        try {
            // Attempt to initialize the WIA Device Manager
            $this->deviceManager = new COM("WIA.DeviceManager");
        } catch (Exception $e) {
            throw new Exception("Failed to initialize WIA Device Manager: " . $e->getMessage());
        }
    }

    public function listDevices()
    {
        $devices = [];
        try {
            // Loop through device infos and fetch device details
            foreach ($this->deviceManager->DeviceInfos as $deviceInfo) {
                $devices[] = [
                    'id' => $deviceInfo->DeviceID,
                    'name' => $deviceInfo->Properties('Name')->Value
                ];
            }
        } catch (Exception $e) {
            throw new Exception("Error while listing devices: " . $e->getMessage());
        }

        return $devices;
    }

    public function connect($deviceId)
    {
        try {
            // Validate deviceId type and log it
            if (empty($deviceId)) {
                throw new Exception("Device ID is required.");
            }
            \Log::info("Connecting to device: " . $deviceId);
    
            $this->device = $this->deviceManager->DeviceInfos($deviceId)->Connect();
    
            if (!$this->device) {
                throw new Exception("Failed to connect to the scanner device with ID: $deviceId.");
            }
    
            \Log::info("Device connected successfully.");
        } catch (Exception $e) {
            \Log::error("Error while connecting to device: " . $e->getMessage());
            throw new Exception("Error while connecting to the device: " . $e->getMessage());
        }
    }

    public function scan($outputPath)
{
    if (!$this->device) {
        throw new Exception("No scanner device connected. Please connect a device first.");
    }

    try {
        \Log::info("Starting scan process...");
        
        // Get the first item (scan item) from the device
        $item = $this->device->Items(1);
        if (!$item) {
            throw new Exception("No items found in the scanner. Please check the scanner.");
        }
    
        \Log::info("Item found, starting transfer...");
    
        // Transfer the image from the scanner
        $image = $item->Transfer();
        if (!$image) {
            throw new Exception("Failed to transfer image from the scanner.");
        }
    
        \Log::info("Image transferred successfully.");

        // Save the image in its native format first (before any conversion)
        $nativeImagePath = storage_path('app/public/scans/') . uniqid() . '.bmp'; // Assuming BMP or other format
        $image->SaveFile($nativeImagePath);

        // Manually convert the image if it's not in the desired format
        $convertedImagePath = $this->convertImage($nativeImagePath, $outputPath);

        \Log::info("Image saved and converted successfully to: " . $convertedImagePath);

        // Delete the original native image after conversion
        if (file_exists($nativeImagePath)) {
            unlink($nativeImagePath); // Delete the original image
            \Log::info("Original native image deleted.");
        }

        return str_replace('C:/xampp/htdocs/yasmin/storage', 'storage', $convertedImagePath);

    } catch (Exception $e) {
        \Log::error("Error during scanning process: " . $e->getMessage());
        throw new Exception("Error during scanning process: " . $e->getMessage());
    }
}

    /**
     * Manually convert the scanned image to the desired format (JPEG/PNG) using GD.
     *
     * @param string $inputImagePath
     * @param string $outputImagePath
     * @return string
     * @throws Exception
     */
    private function convertImage($inputImagePath, $outputImagePath)
    {
        // Get the image info
        $imageInfo = getimagesize($inputImagePath);
        if (!$imageInfo) {
            throw new Exception("Failed to retrieve image info for conversion.");
        }

        $imageType = $imageInfo[2]; // The image type (e.g., 1 = GIF, 2 = JPEG, 3 = PNG)
        
        // Create the image resource from the input image based on its format
        switch ($imageType) {
            case IMAGETYPE_BMP:
            case IMAGETYPE_GIF:
                $image = imagecreatefrombmp($inputImagePath); // For BMP format
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputImagePath); // For JPEG format
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputImagePath); // For PNG format
                break;
            default:
                throw new Exception("Unsupported image format for conversion.");
        }

        // Check if GD successfully created the image resource
        if (!$image) {
            throw new Exception("Failed to load image for conversion.");
        }

        // Convert the image to the desired format (e.g., JPG)
        if (imagejpeg($image, $outputImagePath)) {
            imagedestroy($image);
            return $outputImagePath;
        }

        // If conversion fails
        imagedestroy($image);
        throw new Exception("Failed to convert and save the image.");
    }
}
