<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function stream(Request $request)
    {
        $url = $request->query('url');

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response('Missing or invalid video URL', 400);
        }

        $headers = [
            "Referer: https://rezka.ag",
            "User-Agent: Mozilla/5.0 (Linux; Android 10) AppleWebKit/537.36 Chrome/100.0.0.0 Mobile Safari/537.36",
            "Accept: */*",
            "Connection: keep-alive",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 8192);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
            echo $data;
            return strlen($data);
        });

        header("Content-Type: video/mp4");
        header("Accept-Ranges: bytes");
        header("Access-Control-Allow-Origin: *");

        curl_exec($ch);
        curl_close($ch);
        exit;
    }
}
