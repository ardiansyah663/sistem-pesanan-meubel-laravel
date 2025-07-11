<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Mebel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .cart-bounce {
            animation: bounce 0.6s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 20%, 60%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            80% {
                transform: translateY(-5px);
            }
        }
        
        .notification-slide {
            animation: slideInRight 0.5s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .nav-shadow {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg nav-shadow sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('shop.index') }}" class="text-2xl font-bold text-white text-shadow hover:scale-105 transition-transform duration-300">
                    🏠 Toko Mebel
                </a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="glass-effect text-white px-6 py-3 rounded-full hover:bg-white hover:text-purple-600 transition-all duration-300 flex items-center space-x-2 hover-lift">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0V6a3 3 0 013-3h0a3 3 0 013 3v13.5M13 19V6a3 3 0 013-3h0a3 3 0 013 3v13.5"></path>
                        </svg>
                        <span class="font-semibold">Keranjang</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Success Notification -->
        @if (session('success'))
            <div class="notification-slide bg-gradient-to-r from-green-400 to-green-600 text-white p-4 rounded-lg mb-6 shadow-lg border-l-4 border-green-300">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Error Notification -->
        @if (session('error'))
            <div class="notification-slide bg-gradient-to-r from-red-400 to-red-600 text-white p-4 rounded-lg mb-6 shadow-lg border-l-4 border-red-300">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Content Area -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="gradient-bg text-white mt-16 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-lg font-medium">&copy; 2024 Toko Mebel. Semua hak dilindungi.</p>
            <p class="text-sm opacity-75 mt-2">Melayani dengan sepenuh hati untuk rumah impian Anda</p>
        </div>
    </footer>
</body>
</html>