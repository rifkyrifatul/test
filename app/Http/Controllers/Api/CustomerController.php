<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar semua pelanggan.
     */
    public function index()
    {
        $customers = Customer::orderBy('customer_name', 'asc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar pelanggan berhasil diambil.',
            'data' => $customers
        ], 200);
    }

    /**
     * Menyimpan pelanggan baru.
     */
    public function store(Request $request)
    {
        // Validasi input berdasarkan skema database [cite: 29]
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string|max:255',
            'gender' => 'required|in:Pria,Wanita', // Memastikan nilai hanya 'Pria' atau 'Wanita' [cite: 29]
            'birth_date' => 'required|date', // Memastikan format tanggal valid [cite: 29]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat pelanggan baru
        $customer = Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan baru berhasil ditambahkan.',
            'data' => $customer
        ], 201);
    }
}