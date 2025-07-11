<?php

   namespace App\Filament\Resources;

   use App\Filament\Resources\ProductResource\Pages;
   use App\Models\Product;
   use Filament\Forms;
   use Filament\Resources\Resource;
   use Filament\Tables;

   class ProductResource extends Resource
   {
       protected static ?string $model = Product::class;
       protected static ?string $navigationGroup = 'Shop Management';

       public static function form(Forms\Form $form): Forms\Form
       {
           return $form
               ->schema([
                   Forms\Components\TextInput::make('name')->required(),
                   Forms\Components\Textarea::make('description'),
                   Forms\Components\TextInput::make('price')->numeric()->required(),
                   Forms\Components\Select::make('category_id')
                       ->relationship('category', 'name')
                       ->required(),
                   Forms\Components\FileUpload::make('image')
                       ->image()
                       ->directory('products'),
               ]);
       }

       public static function table(Tables\Table $table): Tables\Table
       {
           return $table
               ->columns([
                   Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                   Tables\Columns\TextColumn::make('price')->money('IDR'),
                   Tables\Columns\TextColumn::make('category.name'),
                   Tables\Columns\ImageColumn::make('image'),
                   Tables\Columns\TextColumn::make('created_at')->dateTime(),
               ])
               ->filters([
                   //
               ])
               ->actions([
                   Tables\Actions\EditAction::make(),
                   Tables\Actions\DeleteAction::make(),
               ])
               ->bulkActions([
                   Tables\Actions\DeleteBulkAction::make(),
               ]);
       }

       public static function getPages(): array
       {
           return [
               'index' => Pages\ListProducts::route('/'),
               'create' => Pages\CreateProduct::route('/create'),
               'edit' => Pages\EditProduct::route('/{record}/edit'),
           ];
       }
   }