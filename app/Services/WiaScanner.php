<?php

namespace App\Services;

use COM;
use Exception;

class WiaScanner
{
    private $deviceManager;
    private $device;

    public function __construct()
    {
        $this->deviceManager = new COM("WIA.DeviceManager");
    }

    public function listDevices()
    {
        $devices = [];
        foreach ($this->deviceManager->DeviceInfos as $deviceInfo) {
            $devices[] = [
                'id' => $deviceInfo->DeviceID,
                'name' => $deviceInfo->Properties('Name')->Value
            ];
        }
        return $devices;
    }

    public function connect($deviceId)
    {
        $this->device = $this->deviceManager->DeviceInfos($deviceId)->Connect();
    }

    public function scan($outputPath)
    {
        if (!$this->device) {
            throw new Exception("No scanner connected");
        }

        $item = $this->device->Items(1);
        $image = $item->Transfer();

        $imageProcess = new COM("WIA.ImageProcess");
        $imageProcess->Filters->Add($imageProcess->FilterInfos("Convert")->FilterID);
        $imageProcess->Filters("Convert")->Properties("FormatID")->Value = "{B96B3CAF-0728-11D3-9D7B-0000F81EF32E}"; // PNG format

        $convertedImage = $imageProcess->Apply($image);
        $convertedImage->SaveFile($outputPath);

        return $outputPath;
    }
}
