<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;

   class Order extends Model
   {
       use HasFactory;

       protected $fillable = [
           'customer_name',
           'customer_address',
           'customer_phone',
           'products',
           'total_price',
           'status',
           'payment_proof',
       ];

       protected $casts = [
           'products' => 'array',
       ];
   }