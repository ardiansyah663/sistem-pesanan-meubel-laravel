<?php

// app/Filament/Widgets/RecentOrders.php
namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class RecentOrders extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->sortable()
                    ->searchable()
                    ->limit(20),
                    
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Telepon')
                    ->searchable()
                    ->limit(15),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancel',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'cancel' => 'Dibatalkan',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.index', $record))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false);
    }
}