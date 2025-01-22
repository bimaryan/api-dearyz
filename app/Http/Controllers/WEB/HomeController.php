<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $response = Http::get('https://bimaryan.serv00.net/api/allphoto/');

            if ($response->successful()) {
                $apiStatus = 'API is Online';
            } else {
                $apiStatus = 'API is Offline';
            }
        } catch (\Exception $e) {
            $apiStatus = 'API is Offline';
        }

        return view('index', compact('apiStatus'));
    }
}
