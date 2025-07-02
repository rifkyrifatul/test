<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            // Kolom: ID, Tipe: PK, int (AI)
            $table->id();
            // Kolom: ID Customer, Tipe: FK, uuid
            $table->foreignUuid('customer_id')->constrained('customers')->onDelete('cascade');
            // Kolom: ID Produk, Tipe: FK, int
              $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // Kolom: Tgl Order, Tipe: timestamp
            $table->timestamp('order_date');
            // Kolom: Jumlah, Tipe: int
            $table->unsignedInteger('quantity');
            // Kolom: Total Harga, Tipe: decimal(10,2)
            $table->decimal('total_price', 10, 2);
            // Kolom: Created At & Updated At, Tipe: timestamp (Auto-generated)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};