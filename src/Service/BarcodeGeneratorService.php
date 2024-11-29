<?php

namespace App\Service;

use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeGeneratorService
{
    public function generateBarcode(string $trackingNumber): string
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($trackingNumber, $generator::TYPE_CODE_128);

        return base64_encode($barcode);
    }
}
