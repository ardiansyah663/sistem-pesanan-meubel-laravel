<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
   public function index()
{
    $cart = session()->get('cart', []);
    
    // Periksa dan perbaiki item di keranjang
    foreach ($cart as $key => &$item) {
        if (!isset($item['image']) || !$item['image']) {
            $product = Product::find($item['id']);
            if ($product) {
                $item['image'] = $product->image ?? 'default.jpg';
            } else {
                // Jika produk tidak ditemukan, hapus dari keranjang
                unset($cart[$key]);
            }
        }
    }
    session()->put('cart', $cart);
    
    return view('cart.index', compact('cart'));
}

   public function add(Request $request, Product $product)
{
    $request->validate([
        'quantity' => 'required|integer|min:1|max:10'
    ]);

    $cart = session()->get('cart', []);
    $requestedQuantity = $request->input('quantity', 1);
    
    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $requestedQuantity;
    } else {
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $requestedQuantity,
            'image' => $product->image ?? 'default.jpg', // Gunakan default jika image null
        ];
    }
    
    session()->put('cart', $cart);
    
    return redirect()->route('cart.index')->with('success', 
        $requestedQuantity . ' ' . $product->name . ' berhasil ditambahkan ke keranjang.');
}

  // Replace method update di CartController Anda dengan ini:

public function update(Request $request, Product $product)
{
    // Debug: Log request data
    Log::info('Cart update request:', [
        'product_id' => $product->id,
        'quantity' => $request->input('quantity'),
        'is_ajax' => $request->ajax(),
        'wants_json' => $request->wantsJson()
    ]);

    $request->validate([
        'quantity' => 'required|integer|min:1|max:10'
    ]);

    $cart = session()->get('cart', []);
    
    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] = $request->input('quantity');
        session()->put('cart', $cart);
        
        if ($request->ajax() || $request->wantsJson()) {
            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $itemTotal = $cart[$product->id]['price'] * $cart[$product->id]['quantity'];
            
            $response = [
                'success' => true,
                'message' => 'Quantity updated successfully',
                'total' => $total,
                'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.'),
                'item_total' => $itemTotal,
                'formatted_item_total' => 'Rp ' . number_format($itemTotal, 0, ',', '.')
            ];
            
            \Log::info('Cart update response:', $response);
            
            return response()->json($response);
        }
        
        return redirect()->route('cart.index')->with('success', 
            'Jumlah ' . $product->name . ' berhasil diperbarui.');
    }
    
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }
    
    return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan di keranjang.');
}

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $productName = $cart[$product->id]['name'];
            unset($cart[$product->id]);
            session()->put('cart', $cart);
            
            return redirect()->route('cart.index')->with('success', 
                $productName . ' berhasil dihapus dari keranjang.');
        }
        
        return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    public function clear()
    {
        // Method baru untuk mengosongkan keranjang
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong. Silakan tambahkan produk terlebih dahulu.');
        }
        
        // Hitung total harga
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        return view('cart.checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string|max:500',
            'customer_phone' => 'required|string|max:20',
        ], [
            'customer_name.required' => 'Nama pelanggan harus diisi.',
            'customer_address.required' => 'Alamat pelanggan harus diisi.',
            'customer_phone.required' => 'Nomor telepon harus diisi.',
            'customer_phone.max' => 'Nomor telepon maksimal 20 karakter.',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        // Validasi bahwa semua produk di keranjang masih ada di tabel products
        $products = [];
        $unavailableProducts = [];
        
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image,
                ];
            } else {
                $unavailableProducts[] = $item['name'];
            }
        }

        // Jika ada produk yang tidak tersedia
        if (!empty($unavailableProducts)) {
            return redirect()->route('cart.index')->with('error', 
                'Produk berikut tidak tersedia: ' . implode(', ', $unavailableProducts));
        }

        $total = collect($products)->sum(fn($item) => $item['price'] * $item['quantity']);

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_phone' => $request->customer_phone,
            'products' => json_encode($products),
            'total_price' => $total,
            'status' => 'pending',
        ]);

        session()->forget('cart');
        
        return redirect()->route('cart.payment-proof', $order)->with('success', 
            'Pesanan berhasil dibuat dengan nomor: #' . $order->id);
    }

    public function paymentProof(Order $order)
    {
        return view('cart.payment-proof', compact('order'));
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048'
        ], [
            'payment_proof.required' => 'Bukti pembayaran harus diunggah.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format file harus jpg, png, jpeg, atau webp.',
            'payment_proof.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Hapus file lama jika ada
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $order->update([
            'payment_proof' => $path,
            'status' => 'pending' // Update status setelah upload bukti pembayaran
        ]);

        return redirect()->route('shop.index')->with('success', 
            'Bukti pembayaran berhasil diunggah. Pesanan Anda sedang diproses.');
    }

    public function getCartCount()
    {
        // Method untuk mendapatkan jumlah item di keranjang (untuk AJAX)
        $cart = session()->get('cart', []);
        $count = collect($cart)->sum('quantity');
        
        return response()->json(['count' => $count]);
    }

    public function getCartTotal()
    {
        // Method untuk mendapatkan total harga keranjang (untuk AJAX)
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        return response()->json([
            'total' => $total,
            'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }

}