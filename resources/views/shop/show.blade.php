@extends('layouts.app')

@section('content')
<style>
    .product-detail-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .product-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
    }
    
    .product-image {
        transition: transform 0.5s ease;
        cursor: zoom-in;
    }
    
    .product-image:hover {
        transform: scale(1.1);
    }
    
    .price-display {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
        padding: 15px 25px;
        border-radius: 25px;
        font-size: 1.5rem;
        font-weight: 700;
        display: inline-block;
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        margin: 15px 0;
    }
    
    .add-to-cart-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        border: none;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        justify-content: center;
    }
    
    .add-to-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }
    
    .add-to-cart-btn:active {
        transform: translateY(-1px);
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
        background: rgba(102, 126, 234, 0.1);
        padding: 15px;
        border-radius: 15px;
    }
    
    .quantity-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .quantity-btn:hover {
        transform: scale(1.1);
    }
    
    .quantity-input {
        width: 60px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: bold;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px;
        background: white;
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        color: #64748b;
    }
    
    .breadcrumb a {
        color: #667eea;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .breadcrumb a:hover {
        color: #764ba2;
    }
    
    .product-features {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 20px;
        border-radius: 15px;
        margin: 20px 0;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        color: #475569;
    }
    
    .feature-icon {
        width: 20px;
        height: 20px;
        color: #667eea;
    }
    
    .rating-stars {
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 15px 0;
    }
    
    .star {
        color: #fbbf24;
        font-size: 1.5rem;
    }
    
    .fade-in {
        animation: fadeInUp 0.8s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .image-gallery {
        display: grid;
        grid-template-columns: 1fr 100px;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .thumbnail {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.2s ease;
    }
    
    .thumbnail:hover,
    .thumbnail.active {
        border-color: #667eea;
    }
    
    .back-btn {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }
    
    .back-btn:hover {
        background: #667eea;
        color: white;
        transform: translateX(-5px);
    }
    
    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        margin: 10px 0;
    }
    
    .in-stock {
        background: rgba(34, 197, 94, 0.1);
        color: #059669;
    }
    
    .out-of-stock {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb fade-in">
    <a href="{{ route('shop.index') }}">Beranda</a>
    <span>></span>
    <span>{{ $product->category->name ?? 'Produk' }}</span>
    <span>></span>
    <span class="font-semibold">{{ $product->name }}</span>
</div>

<!-- Back Button -->
<a href="{{ route('shop.index') }}" class="back-btn fade-in">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali ke Beranda
</a>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-in">
    <!-- Product Images -->
    <div class="space-y-4">
        <div class="product-image-container">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="product-image w-full h-96 object-cover"
                     id="mainImage">
            @else
                <div class="w-full h-96 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">Gambar tidak tersedia</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Thumbnail Gallery (mockup) -->
        <div class="flex gap-3 overflow-x-auto">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="thumbnail active">
                <!-- Mock additional images -->
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="thumbnail opacity-60">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="thumbnail opacity-60">
            @endif
        </div>
    </div>

    <!-- Product Details -->
    <div class="space-y-6">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
            
            <!-- Rating -->
            <div class="rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star">★</span>
                @endfor
                <span class="text-gray-600 ml-2">(128 ulasan)</span>
            </div>
            
            <!-- Stock Status -->
            <div class="stock-status in-stock">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Tersedia
            </div>
            
            <!-- Price -->
            <div class="price-display">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
        </div>

        <!-- Description -->
        <div class="product-detail-card p-6">
            <h3 class="text-xl font-semibold mb-3 text-gray-800">Deskripsi Produk</h3>
            <p class="text-gray-700 leading-relaxed">
                {{ $product->description ?? 'Furniture berkualitas tinggi dengan desain modern dan material premium. Cocok untuk melengkapi rumah Anda dengan sentuhan elegan dan fungsional.' }}
            </p>
        </div>

        <!-- Product Features -->
        <div class="product-features">
            <h3 class="text-xl font-semibold mb-3 text-gray-800">Keunggulan Produk</h3>
            <div class="feature-item">
                <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Material berkualitas tinggi</span>
            </div>
            <div class="feature-item">
                <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Garansi resmi 1 tahun</span>
            </div>
            <div class="feature-item">
                <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Gratis ongkir dalam kota</span>
            </div>
            <div class="feature-item">
                <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
                <span>Konsultasi gratis dengan desainer</span>
            </div>
        </div>

        <!-- Add to Cart Form -->
        <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Quantity Selector -->
            <div class="quantity-selector">
                <label class="font-semibold text-gray-700">Jumlah:</label>
                <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                <input type="number" 
                       name="quantity" 
                       id="quantity" 
                       value="1" 
                       min="1" 
                       max="10"
                       class="quantity-input"
                       readonly>
                <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
            </div>

            <!-- Add to Cart Button -->
            <button type="submit" class="add-to-cart-btn" id="addToCartBtn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0V6a3 3 0 013-3h0a3 3 0 013 3v13.5M13 19V6a3 3 0 013-3h0a3 3 0 013 3v13.5"/>
                </svg>
                <span>Tambah ke Keranjang</span>
            </button>
        </form>

        <!-- Additional Info -->
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Gratis Ongkir</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
                <span>Bayar di Tempat</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span>Bisa Return</span>
            </div>
        </div>
    </div>
</div>

<script>
// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const minValue = parseInt(quantityInput.min);
    
    if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
    }
}

// Add to cart animation
document.getElementById('addToCartBtn').addEventListener('click', function(e) {
    const button = this;
    const originalText = button.innerHTML;
    
    // Change button text temporarily
    button.innerHTML = `
        <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-8h-4M4 12H0m15.364-6.364l-2.828 2.828M6.464 6.464L3.636 3.636m12.728 12.728l-2.828-2.828M6.464 17.536L3.636 20.364"/>
        </svg>
        <span>Menambahkan...</span>
    `;
    
    // Reset after form submission
    setTimeout(() => {
        button.innerHTML = originalText;
    }, 1000);
});

// Image gallery functionality
const thumbnails = document.querySelectorAll('.thumbnail');
const mainImage = document.getElementById('mainImage');

thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
        // Remove active class from all thumbnails
        thumbnails.forEach(t => t.classList.remove('active'));
        
        // Add active class to clicked thumbnail
        this.classList.add('active');
        
        // Change main image
        if (mainImage) {
            mainImage.src = this.src;
        }
    });
});

// Add smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});
</script>
@endsection