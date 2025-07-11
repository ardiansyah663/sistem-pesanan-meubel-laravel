<?php

   namespace App\Filament\Resources;

   use App\Filament\Resources\CategoryResource\Pages;
   use App\Models\Category;
   use Filament\Forms;
   use Filament\Resources\Resource;
   use Filament\Tables;

   class CategoryResource extends Resource
   {
       protected static ?string $model = Category::class;
       protected static ?string $navigationGroup = 'Shop Management';

       public static function form(Forms\Form $form): Forms\Form
       {
           return $form
               ->schema([
                   Forms\Components\TextInput::make('name')->required(),
               ]);
       }

       public static function table(Tables\Table $table): Tables\Table
       {
           return $table
               ->columns([
                   Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
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
               'index' => Pages\ManageCategories::route('/'),
           ];
       }
   }