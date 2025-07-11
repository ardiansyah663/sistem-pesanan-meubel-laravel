@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Keranjang Belanja</h1>
    
    @if (!empty($cart))
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-4 text-left text-sm font-semibold text-gray-600">Produk</th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-600">Harga</th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-600">Jumlah</th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-600">Total</th>
                            <th class="p-4 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4">
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ asset('storage/' . ($item['image'] ?? 'default.jpg')) }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-16 h-16 object-cover rounded">
                                        <span class="text-gray-800">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td class="p-4">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                class="update-quantity bg-gray-200 text-gray-600 px-3 py-1 rounded hover:bg-gray-300 transition-colors"
                                                data-id="{{ $item['id'] }}"
                                                data-action="decrease">−</button>
                                        <input type="number" 
                                               class="w-16 text-center border rounded px-2 py-1 quantity-input"
                                               value="{{ $item['quantity'] }}"
                                               min="1" max="10"
                                               data-id="{{ $item['id'] }}"
                                               readonly>
                                        <button type="button" 
                                                class="update-quantity bg-gray-200 text-gray-600 px-3 py-1 rounded hover:bg-gray-300 transition-colors"
                                                data-id="{{ $item['id'] }}"
                                                data-action="increase">+</button>
                                    </div>
                                </td>
                                <td class="p-4 item-total">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                <td class="p-4">
                                    <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <a href="{{ route('cart.clear') }}" 
                           class="text-red-500 hover:text-red-600 font-medium">Kosongkan Keranjang</a>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-gray-800">Total: <span id="cart-total">Rp {{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 0, ',', '.') }}</span></p>
                        <a href="{{ route('cart.checkout') }}" 
                           class="mt-4 inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                            Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <p class="text-gray-600 mb-4">Keranjang belanja Anda kosong.</p>
            <a href="{{ route('shop.index') }}" 
               class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                Belanja Sekarang
            </a>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('.update-quantity');
    
    updateButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            const quantityContainer = this.parentElement;
            const input = quantityContainer.querySelector('.quantity-input');
            const currentRow = this.closest('tr');
            let quantity = parseInt(input.value);

            if (action === 'increase' && quantity < 10) {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            } else {
                return;
            }

            const rowButtons = currentRow.querySelectorAll('.update-quantity');
            rowButtons.forEach(function(btn) {
                btn.disabled = true;
                btn.style.opacity = '0.5';
            });

            const originalText = this.textContent;
            this.textContent = '...';

            fetch('{{ url("/cart/update") }}/' + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    quantity: quantity 
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    input.value = quantity;
                    
                    const itemTotal = currentRow.querySelector('.item-total');
                    itemTotal.textContent = data.formatted_item_total;
                    
                    const cartTotal = document.querySelector('#cart-total');
                    if (cartTotal) {
                        cartTotal.textContent = data.formatted_total;
                    }

                    updateCartCount();
                    
                    showMessage('Jumlah berhasil diperbarui', 'success');
                } else {
                    throw new Error(data.message || 'Gagal memperbarui jumlah');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showMessage('Terjadi kesalahan: ' + error.message, 'error');
                
                input.value = action === 'increase' ? quantity - 1 : quantity + 1;
            })
            .finally(function() {
                rowButtons.forEach(function(btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                });
                
                button.textContent = originalText;
            });
        });
    });

    function updateCartCount() {
        fetch('{{ route("cart.count") }}')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count;
                }
            })
            .catch(function(error) {
                console.error('Error updating cart count:', error);
            });
    }

    function showMessage(message, type) {
        const existingMessage = document.querySelector('.cart-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = 'cart-message fixed top-4 right-4 px-4 py-2 rounded-lg z-50 ' + 
                              (type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white');
        messageDiv.textContent = message;
        
        document.body.appendChild(messageDiv);
        
        setTimeout(function() {
            messageDiv.remove();
        }, 3000);
    }
});
</script>

@endsection
