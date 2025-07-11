{{-- resources/views/filament/modals/payment-proof.blade.php --}}

<div class="payment-proof-modal">
    @if($record->payment_proof)
        <div class="text-center">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Bukti Pembayaran - Order #{{ $record->id }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $record->customer_name }}
                </p>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-4">
                <a href="{{ Storage::url($record->payment_proof) }}" target="_blank">
                    <img 
                        src="{{ Storage::url($record->payment_proof) }}" 
                        alt="Bukti Pembayaran" 
                        class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                        style="max-height: 70vh; object-fit: contain;"
                    >
                </a>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                <p><strong>Total Pesanan:</strong> Rp {{ number_format($record->total_price, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> 
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $record->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                           ($record->status === 'canceled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($record->status) }}
                    </span>
                </p>
                <p><strong>Tanggal Upload:</strong> {{ $record->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Tidak Ada Bukti Pembayaran
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Pelanggan belum mengunggah bukti pembayaran untuk pesanan ini.
            </p>
        </div>
    @endif
</div>

<style>
    .payment-proof-modal img {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .payment-proof-modal img:hover {
        transform: scale(1.02);
    }
</style>
