<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('orders', function (Blueprint $table) {
               $table->id();
               $table->string('customer_name');
               $table->text('customer_address');
               $table->string('customer_phone');
               $table->json('products'); // Simpan produk sebagai JSON
               $table->decimal('total_price', 10, 2);
               $table->enum('status', ['pending', 'confirmed'])->default('pending');
               $table->string('payment_proof')->nullable();
               $table->timestamps();
           });
       }

       public function down(): void
       {
           Schema::dropIfExists('orders');
       }
   };