@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Upload Bukti Pembayaran</h1>
            <p class="text-gray-600">Konfirmasi pembayaran untuk menyelesaikan pesanan Anda</p>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Detail Pesanan
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gray-100 rounded-full p-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Order ID</p>
                                <p class="font-semibold text-gray-800">#{{ $order->id }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-emerald-100 rounded-full p-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Pembayaran</p>
                                <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Information Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Informasi Rekening
                </h2>
            </div>
            
            <div class="p-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-500 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v2H2V6zM2 10v8a2 2 0 002 2h16a2 2 0 002-2v-8H2z"/>
                                    <path d="M6 14h4v2H6v-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-600">Bank BCA</p>
                                <p class="text-2xl font-bold text-gray-800">1234567890</p>
                                <p class="text-sm text-gray-600">a/n <strong>Toko Mebel</strong></p>
                            </div>
                        </div>
                        <button onclick="copyToClipboard('1234567890', event)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span>Copy</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Bukti Pembayaran
                </h2>
            </div>

            <form action="{{ route('cart.upload-payment-proof', $order) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                <div class="mb-6">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-4">
                        Bukti Pembayaran
                    </label>
                    
                    <!-- File Upload Area -->
                    <div class="relative">
                        <input type="file" 
                               name="payment_proof" 
                               id="payment_proof" 
                               class="hidden" 
                               accept="image/*,.pdf"
                               required
                               onchange="handleFileSelect(event)">
                        
                        <div onclick="document.getElementById('payment_proof').click()" 
                             class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all duration-200"
                             id="dropzone">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-lg font-medium text-gray-700 mb-2">Klik untuk upload file</p>
                            <p class="text-sm text-gray-500 mb-4">atau drag and drop file ke area ini</p>
                            <p class="text-xs text-gray-400">Format yang didukung: JPG, PNG, PDF (Max: 5MB)</p>
                        </div>
                        
                        <!-- File Preview -->
                        <div id="filePreview" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700" id="fileName"></p>
                                    <p class="text-xs text-gray-500" id="fileSize"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span>Upload Bukti Pembayaran</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl p-6 border-l-4 border-amber-500">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Petunjuk Upload:</h3>
                    <ul class="text-sm text-amber-700 space-y-1">
                        <li>• Pastikan bukti pembayaran jelas dan dapat dibaca</li>
                        <li>• Format file yang didukung: JPG, PNG, atau PDF</li>
                        <li>• Ukuran file maksimal 5MB</li>
                        <li>• Pesanan akan diproses setelah bukti pembayaran diverifikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, event) {
    event.preventDefault();
    
    // Fallback for older browsers
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            showCopyFeedback(event.target.closest('button'));
        }).catch(function() {
            // Fallback to older method
            fallbackCopyTextToClipboard(text, event.target.closest('button'));
        });
    } else {
        // Fallback for browsers without clipboard API
        fallbackCopyTextToClipboard(text, event.target.closest('button'));
    }
}

function fallbackCopyTextToClipboard(text, button) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showCopyFeedback(button);
    } catch (err) {
        console.error('Unable to copy to clipboard', err);
    }
    
    document.body.removeChild(textArea);
}

function showCopyFeedback(button) {
    const originalText = button.innerHTML;
    button.innerHTML = `
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>Copied!</span>
    `;
    button.classList.add('bg-green-500', 'hover:bg-green-600');
    button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('bg-green-500', 'hover:bg-green-600');
        button.classList.add('bg-blue-500', 'hover:bg-blue-600');
    }, 2000);
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        
        document.getElementById('fileName').textContent = fileName;
        document.getElementById('fileSize').textContent = fileSize;
        document.getElementById('filePreview').classList.remove('hidden');
        
        // Update dropzone appearance
        const dropzone = document.getElementById('dropzone');
        dropzone.classList.add('border-green-500', 'bg-green-50');
        dropzone.classList.remove('border-gray-300');
        
        // Show image preview if it's an image file
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                showImagePreview(e.target.result, fileName);
            };
            reader.readAsDataURL(file);
        } else {
            // Hide image preview for non-image files
            const existingPreview = document.getElementById('imagePreview');
            if (existingPreview) {
                existingPreview.remove();
            }
        }
    }
}

function showImagePreview(imageSrc, fileName) {
    // Remove existing preview if any
    const existingPreview = document.getElementById('imagePreview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    // Create new preview
    const previewHTML = `
        <div id="imagePreview" class="mt-6 bg-white rounded-xl border-2 border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Preview Gambar
            </h3>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="md:w-1/2">
                    <img src="${imageSrc}" alt="Preview ${fileName}" class="w-full h-auto max-h-96 object-contain rounded-lg border border-gray-200 shadow-sm">
                </div>
                <div class="md:w-1/2 space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-700 mb-2">Detail File:</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Nama File:</span>
                                <span class="font-medium">${fileName}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ukuran:</span>
                                <span class="font-medium">${(new File([imageSrc], fileName).size / 1024 / 1024).toFixed(2)} MB</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-800">File berhasil dipilih</span>
                        </div>
                        <p class="text-xs text-green-700 mt-1">Gambar siap untuk diupload</p>
                    </div>
                    <button type="button" onclick="removeFile()" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Hapus File</span>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Insert preview after the form
    const form = document.querySelector('form');
    form.insertAdjacentHTML('afterend', previewHTML);
}

function removeFile() {
    // Clear file input
    document.getElementById('payment_proof').value = '';
    
    // Hide file preview
    document.getElementById('filePreview').classList.add('hidden');
    
    // Remove image preview
    const imagePreview = document.getElementById('imagePreview');
    if (imagePreview) {
        imagePreview.remove();
    }
    
    // Reset dropzone appearance
    const dropzone = document.getElementById('dropzone');
    dropzone.classList.remove('border-green-500', 'bg-green-50');
    dropzone.classList.add('border-gray-300');
}

// Drag and drop functionality
const dropzone = document.getElementById('dropzone');

dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('border-purple-500', 'bg-purple-50');
});

dropzone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-purple-500', 'bg-purple-50');
});

dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-purple-500', 'bg-purple-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const fileInput = document.getElementById('payment_proof');
        fileInput.files = files;
        handleFileSelect({ target: { files: files } });
    }
});
</script>
@endsection