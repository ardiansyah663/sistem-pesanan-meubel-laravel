<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Shop Management';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->disabled(),

                Forms\Components\Textarea::make('customer_address')
                    ->label('Alamat Pelanggan')
                    ->disabled(),

                Forms\Components\TextInput::make('customer_phone')
                    ->label('Nomor WhatsApp')
                    ->disabled(),

                Repeater::make('products')
                    ->label('Produk yang Dibeli')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->disabled(),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(3)
                    ->disabled()
                    ->default([])
                    ->mutateRelationshipDataBeforeFillUsing(fn (array $data): array => is_array($data) ? $data : [])
                    ->afterStateHydrated(function (Repeater $component, $state, $record) {
                        if ($record && $record->products) {
                            $products = $record->products;
                            if (is_array($products) && !empty($products)) {
                                $component->state($products);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(),

                Forms\Components\Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'canceled'  => 'Canceled',
                    ])
                    ->disabled(),

                Forms\Components\FileUpload::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->directory('payment_proofs')
                    ->imageEditor()
                    ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                    ->disabled(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('No. WhatsApp')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'confirmed' => 'success',
                        'canceled'  => 'danger',
                    }),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->size(40)
                    ->circular()
                    ->defaultImageUrl(asset('images/no-image.png'))
                    ->action(
                        Action::make('view_payment_proof')
                            ->modalHeading('Bukti Pembayaran')
                            ->modalContent(fn (Order $record): \Illuminate\Contracts\View\View => view('filament.modals.payment-proof', ['record' => $record]))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                            ->slideOver()
                    ),

                Tables\Columns\TextColumn::make('products')
                    ->label('Produk')
                    ->formatStateUsing(function ($state) {
                        $products = [];
                        if (is_string($state) && !empty($state)) {
                            $decoded = json_decode($state, true);
                            if (is_array($decoded)) {
                                $products = $decoded;
                            }
                        } elseif (is_array($state)) {
                            $products = $state;
                        }

                        if (!empty($products)) {
                            return collect($products)->map(function ($product) {
                                $name     = $product['name'] ?? 'Produk Tidak Diketahui';
                                $quantity = $product['quantity'] ?? 0;
                                return "{$name} (x{$quantity})";
                            })->implode(', ');
                        }

                        return 'Tidak ada produk';
                    })
                    ->wrap(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'canceled'  => 'Canceled',
                    ]),
            ])
            ->actions([
                Action::make('view_payment_proof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Bukti Pembayaran')
                    ->modalContent(fn (Order $record): \Illuminate\Contracts\View\View => view('filament.modals.payment-proof', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->slideOver()
                    ->visible(fn (Order $record): bool => !empty($record->payment_proof)),

                Action::make('confirm')
                    ->label('Confirm')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pesanan')
                    ->modalDescription('Apakah Anda yakin ingin mengkonfirmasi pesanan ini?')
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->action(fn (Order $record) => $record->update(['status' => 'confirmed']))
                    ->successNotificationTitle('Pesanan berhasil dikonfirmasi')
                    ->visible(fn (Order $record): bool => $record->status === 'pending'),

                Action::make('cancel')
                    ->label('Cancel')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Pesanan')
                    ->modalDescription('Apakah Anda yakin ingin membatalkan pesanan ini?')
                    ->modalSubmitActionLabel('Ya, Batalkan')
                    ->action(fn (Order $record) => $record->update(['status' => 'canceled']))
                    ->successNotificationTitle('Pesanan berhasil dibatalkan')
                    ->visible(fn (Order $record): bool => $record->status === 'pending'),

                Action::make('update_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending'   => 'Pending',
                                'confirmed' => 'Confirmed',
                                'canceled'  => 'Canceled',
                            ])
                            ->required(),
                    ])
                    ->modalHeading('Ubah Status Pesanan')
                    ->modalSubmitActionLabel('Simpan')
                    ->requiresConfirmation()
                    ->modalDescription('Pilih status baru untuk pesanan ini.')
                    ->action(fn (array $data, Order $record) => $record->update(['status' => $data['status']]))
                    ->successNotificationTitle('Status pesanan berhasil diubah'),

                Action::make('whatsapp')
    ->label('Kirim WA')
    ->url(function (Order $record): string {
        $customerName = trim(preg_replace('/[^\w\s]/', '', $record->customer_name));
        $phoneNumber  = preg_replace('/\D/', '', $record->customer_phone);

        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }

        $productsText = '';
        $products     = [];

        if (is_string($record->products) && !empty($record->products)) {
            $decoded = json_decode($record->products, true);
            if (is_array($decoded)) {
                $products = $decoded;
            }
        } elseif (is_array($record->products)) {
            $products = $record->products;
        }

        if (!empty($products)) {
            $productLines = [];
            foreach ($products as $product) {
                $name     = $product['name'] ?? 'Produk Tidak Diketahui';
                $quantity = $product['quantity'] ?? 0;
                $price    = isset($product['price']) ? number_format($product['price'], 0, ',', '.') : '0';
                $productLines[] = "• {$name} (x{$quantity}) - Rp {$price}";
            }
            $productsText = implode("\n", $productLines);
        } else {
            $productsText = "• Tidak ada detail produk";
        }

        $totalPrice = number_format($record->total_price ?? 0, 0, ',', '.');

        $message = "Hai {$customerName},\n\n";
        if ($record->status === 'confirmed') {
            $message .= "Pesanan Anda dengan ID #{$record->id} telah *DIKONFIRMASI*. ✅\n\n";
        } elseif ($record->status === 'canceled') {
            $message .= "Pesanan Anda dengan ID #{$record->id} telah *DIBATALKAN*. ❌ Maaf Atas Ketidaknyamanan Anda\n\n ";
        } else {
            $message .= "Pesanan Anda dengan ID #{$record->id} sedang dalam status *{$record->status}*.\n\n";
        }

        $message .= "Detail Pesanan:\n{$productsText}\n\n";
        $message .= "Total: Rp {$totalPrice}\n\n";
        $message .= "Pada tanggal: {$record->created_at->format('d M Y H:i')}\n\n";
        
        // Menyertakan link bukti pembayaran
        if ($record->payment_proof) {
            $paymentProofUrl = asset('storage/' . $record->payment_proof);
            $message .= "Bukti Pembayaran: {$paymentProofUrl}\n\n";
        } else {
            $message .= "Bukti Pembayaran: Tidak ada bukti pembayaran yang diunggah.\n\n";
        }
        
        $message .= "Jika ada pertanyaan atau butuh bantuan, silakan hubungi kami.\n";
        $message .= "Terima kasih atas kepercayaan Anda kepada kami! 🙏";

        return 'https://wa.me/' . $phoneNumber . '?text=' . urlencode($message);
    })
    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Hapus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
