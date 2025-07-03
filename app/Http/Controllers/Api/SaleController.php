<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Menampilkan riwayat transaksi penjualan.
     */
    public function index()
    {
        // Ambil data penjualan dan sertakan data relasi customer dan product
        $sales = Sale::with(['customer', 'product'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat transaksi berhasil diambil.',
            'data' => $sales
        ], 200);
    }

    /**
     * Membuat transaksi penjualan baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari user
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            // 2. Gunakan DB Transaction untuk menjaga konsistensi data
            $sale = DB::transaction(function () use ($validatedData) {
                $product = Product::findOrFail($validatedData['product_id']);

                // 3. Validasi stok sebelum transaksi 
                if ($product->stock < $validatedData['quantity']) {
                    // Jika stok tidak cukup, batalkan transaksi dengan melempar exception
                    throw new \Exception('Stok produk tidak mencukupi.');
                }

                // 4. Hitung total harga (quantity * price) 
                $totalPrice = $validatedData['quantity'] * $product->price;

                // 5. Buat record penjualan baru
                $newSale = Sale::create([
                    'customer_id' => $validatedData['customer_id'],
                    'product_id' => $validatedData['product_id'],
                    'quantity' => $validatedData['quantity'],
                    'total_price' => $totalPrice,
                ]);

                // 6. Kurangi stok setelah transaksi sukses 
                $product->decrement('stock', $validatedData['quantity']);

                return $newSale;
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat.',
                'data' => $sale
            ], 201);

        } catch (\Exception $e) {
            // Tangani error (misal: stok tidak cukup)
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}