<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    protected $apiUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    private function fetchData($endpoint)
    {
        $response = Http::get("{$this->apiUrl}/{$endpoint}");
        if ($response->successful()) {
            return $response->json();
        }
        return [];
    }

    public function getCities($province_id)
    {
        return response()->json($this->fetchData("regencies/{$province_id}.json"));
    }

    public function getDistricts($city_id)
    {
        return response()->json($this->fetchData("districts/{$city_id}.json"));
    }

    public function getVillages($district_id)
    {
        return response()->json($this->fetchData("villages/{$district_id}.json"));
    }
}