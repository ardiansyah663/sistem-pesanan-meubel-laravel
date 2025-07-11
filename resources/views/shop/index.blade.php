@extends('layouts.app')

@section('content')
<style>
    .product-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    }
    
    .product-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .product-image {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    .category-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .price-tag {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }
    
    .detail-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .detail-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        text-decoration: none;
        color: white;
    }
    
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .search-bar {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        padding: 12px 24px;
        color: white;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        outline: none;
        transition: all 0.3s ease;
    }
    
    .search-bar:focus {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
    }
    
    .search-bar::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .category-section {
        margin-bottom: 3rem;
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
    
    .stagger-1 { animation-delay: 0.1s; }
    .stagger-2 { animation-delay: 0.2s; }
    .stagger-3 { animation-delay: 0.3s; }
    .stagger-4 { animation-delay: 0.4s; }
    .stagger-5 { animation-delay: 0.5s; }
    .stagger-6 { animation-delay: 0.6s; }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
            🛋️ Toko Mebel
        </h1>
        <p class="text-xl text-white opacity-90 mb-6">
            Temukan furniture berkualitas untuk rumah impian Anda
        </p>
        <div class="flex justify-center">
            <input type="text" 
                   class="search-bar" 
                   placeholder="Cari produk mebel..." 
                   id="searchInput"
                   onkeyup="searchProducts()">
        </div>
    </div>
</div>

<!-- Products by Category -->
@foreach ($categories as $categoryIndex => $category)
    <div class="category-section fade-in" style="animation-delay: {{ $categoryIndex * 0.2 }}s;">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold category-header">
                {{ $category->name }}
            </h2>
            <div class="h-1 flex-1 mx-4 bg-gradient-to-r from-purple-400 to-transparent rounded"></div>
            <span class="text-gray-500 font-medium">{{ $category->products->count() }} produk</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            @foreach ($category->products as $productIndex => $product)
                <div class="product-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 fade-in stagger-{{ ($productIndex % 6) + 1 }}" 
                     data-category="{{ strtolower($category->name) }}" 
                     data-product="{{ strtolower($product->name) }}">
                    
                    <!-- Product Image -->
                    <div class="relative overflow-hidden">
                        @if ($product->image)
                            <div class="product-image">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                            </div>
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Quick View Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <a href="{{ route('shop.show', $product) }}" 
                               class="bg-white text-purple-600 px-4 py-2 rounded-full font-semibold hover:bg-purple-600 hover:text-white transition-colors duration-300">
                                Quick View
                            </a>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="price-tag">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            <div class="flex items-center text-yellow-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        
                        <a href="{{ route('shop.show', $product) }}" 
                           class="detail-button w-full text-center block">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

@if($categories->isEmpty())
    <div class="text-center py-16">
        <div class="text-6xl mb-4">🏠</div>
        <h3 class="text-2xl font-bold text-gray-600 mb-2">Belum Ada Produk</h3>
        <p class="text-gray-500">Produk-produk mebel akan segera hadir!</p>
    </div>
@endif

<script>
function searchProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        const productName = card.getAttribute('data-product');
        const categoryName = card.getAttribute('data-category');
        
        if (productName.includes(searchTerm) || categoryName.includes(searchTerm)) {
            card.style.display = 'block';
            card.classList.add('fade-in');
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide category headers based on visible products
    const categoryHeaders = document.querySelectorAll('.category-section');
    categoryHeaders.forEach(section => {
        const visibleProducts = section.querySelectorAll('.product-card[style*="display: block"], .product-card:not([style*="display: none"])');
        if (visibleProducts.length === 0 && searchTerm !== '') {
            section.style.display = 'none';
        } else {
            section.style.display = 'block';
        }
    });
}

// Add smooth scrolling to navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Add loading animation
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
});
</script>
@endsection