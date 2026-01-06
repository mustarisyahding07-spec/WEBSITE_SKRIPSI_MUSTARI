<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Setting;
use App\Services\FonnteService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Customer Account')
                    ->placeholder('Guest Order'),
                
                Forms\Components\TextInput::make('customer_name')
                    ->label('Guest Name')
                    ->visible(fn ($get) => $get('user_id') === null),
                
                Forms\Components\Textarea::make('customer_address')
                    ->label('Delivery Address')
                    ->columnSpanFull(),
                
                Forms\Components\TextInput::make('customer_phone')
                    ->tel()
                    ->label('Phone Number'),

                Forms\Components\Textarea::make('items_json')
                    ->label('Order Items (JSON)')
                    ->columnSpanFull()
                    ->disabled() // Read-only for now
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT)),

                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('tracking_number')
                    ->label('Resi / Tracking Number')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($state, Order $record) => $record->user ? $record->user->name : ($state ?? 'Guest')),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'pending')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'processing']);
                        
                        $bankInfo = Setting::where('key', 'bank_account_info')->value('value') ?? 'BCA 1234567890 a.n Ivo Karya';
                        $message = "Halo {$record->customer_name}, pesanan #{$record->id} telah DIKONFIRMASI. Total: Rp " . number_format($record->total_amount, 0, ',', '.') . ". Silakan transfer ke {$bankInfo}. Kirim bukti bayar ke sini ya. Terima kasih!";
                        
                        $target = $record->customer_phone ?? $record->user?->phone_number;
                        if ($target) {
                            FonnteService::send($target, $message);
                            
                            // Also notify Admin if needed, or logging is handled in service
                            \Filament\Notifications\Notification::make()
                                ->title('Tagihan Terkirim ke WA User')
                                ->success()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('ship')
                    ->label('Kirim Resi')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->status === 'processing')
                    ->form([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'status' => 'shipped',
                            'tracking_number' => $data['tracking_number']
                        ]);
                        
                        $trackingUrl = route('order.track', $record->tracking_token);
                        $message = "Pesanan #{$record->id} sedang dikirim! \n\nNomor Resi: {$data['tracking_number']} \n\nLacak posisi paketmu secara real-time di sini: {$trackingUrl}";
                        
                        $target = $record->customer_phone ?? $record->user?->phone_number;
                        if ($target) {
                            FonnteService::send($target, $message);

                             \Filament\Notifications\Notification::make()
                                ->title('Resi Terkirim ke WA User')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
