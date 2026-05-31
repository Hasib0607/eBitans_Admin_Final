<?php

namespace App\Http\Controllers;

use App\Models\Store;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function downloadQrCode($id)
    {
        $store = null;
        if (isset($id)) {
            $store = Store::where('id', $id)->first();
        }

        // Define the URL for the QR code
        $url = $store->url ?? env('APP_URL');

        $svgDir = public_path('/qrcodes');
        $filePath = $svgDir . $url . '.svg';

        // Generate the QR code and save it as a PNG file
        QrCode::format('svg')
            ->size(300)
            ->generate($url, $filePath);

        // Return the file as a downloadable response
        return response()->download($filePath)->deleteFileAfterSend(true);

    }
}
