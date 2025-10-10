<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function index()
    {
        // Data Order Items (biasanya dari database atau session)
        $orderItems = [
            [
                'id' => 1,
                'name' => 'Deluxe Rose Bouquet',
                'quantity' => 1,
                'price' => 750000,
                'imageUrl' => 'https://placehold.co/120x120/f2e8f4/7c3aed?text=Buket+Mawar',
            ],
            [
                'id' => 2,
                'name' => 'Sunshine Lily Arrangement',
                'quantity' => 2,
                'price' => 450000,
                'imageUrl' => 'https://placehold.co/120x120/fffbe6/f59e0b?text=Buket+Lily',
            ]
        ];

        // Ambil data provinsi untuk pertama kali load
        $provincesResponse = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $provinces = $provincesResponse->successful() ? $provincesResponse->json() : [];

        return view('checkout', [
            'orderItems' => $orderItems,
            'provinces' => $provinces
        ]);
    }

    /**
     * Menghitung ongkos kirim.
     */
    public function calculateShipping(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            // Tambahkan validasi lain jika perlu
        ]);

        // Jika validasi gagal, kembalikan error sebagai JSON
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // --- Logika API RajaOngkir ---
        // Ganti dengan API Key Anda dan logika yang sesungguhnya.
        // Di sini kita hanya simulasi seperti di kode React Anda.
        $mockShippingCost = [
            'service' => 'REG',
            'description' => 'Layanan Reguler',
            'cost' => [['value' => rand(15000, 35000), 'etd' => '2-3']]
        ];

        // Kembalikan data ongkos kirim sebagai respons JSON yang sukses
        return response()->json(['cost' => $mockShippingCost]);
    }
}