<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShelfResource\Pages;
use App\Filament\Resources\ShelfResource\RelationManagers;
use App\Models\Category;
use App\Models\Shelf;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;

class ShelfResource extends Resource
{
    protected static ?string $model = Shelf::class;

    protected static ?string $navigationIcon = 'heroicon-o-table';

    protected static ?string $navigationGroup = 'Book Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->unique()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('books_count')->counts('books')
                    ->url(fn(Shelf $record) => BookResource::getUrl('index', ['tableFilters[shelf_id][value]' => $record->id]))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Shelf $record): bool => !$record->books()->exists())
            ])
            ->headerActions([
                ExportAction::make('export')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageShelves::route('/'),
        ];
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
