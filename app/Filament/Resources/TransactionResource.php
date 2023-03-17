<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Credential')
                        ->schema([
                            Forms\Components\TextInput::make('full_name')->maxLength(255)->required(),
                            Forms\Components\TextInput::make('email')->email(),
                            Forms\Components\TextInput::make('nisn')->numeric(),
                            Forms\Components\Select::make('class')->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                            ])
                        ]),
                    Forms\Components\Wizard\Step::make('Book Selection')
                        ->schema([
                            Forms\Components\Select::make('book_id')
                                ->relationship('book', 'title')
                                ->placeholder('Select Book')
                        ]),

                    Forms\Components\Wizard\Step::make('Duration')
                        ->schema([
                            Forms\Components\DateTimePicker::make('pickup_date')
                                ->autofocus()
                                ->displayFormat($format = 'F j, Y H:i:s')
                                ->firstDayOfWeek($day = 1)
                                ->format($format = 'Y-m-d H:i:s')
                                ->placeholder('Set pickup time')
                                ->weekStartsOnMonday()
                                ->weekStartsOnSunday()
                                ->withoutSeconds(),
                            Forms\Components\DateTimePicker::make('return_date')
                                ->autofocus()
                                ->displayFormat($format = 'F j, Y H:i:s')
                                ->firstDayOfWeek($day = 1)
                                ->format($format = 'Y-m-d H:i:s')
                                ->placeholder('Set pickup time')
                                ->weekStartsOnMonday()
                                ->weekStartsOnSunday()
                                ->withoutSeconds(),
                        ]),
                ])
                ->columnSpan('full'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name'),
                Tables\Columns\TextColumn::make('nisn')
                    ->copyable()
                    ->copyMessage('Transaction NISN number copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('class')->sortable(),
                Tables\Columns\TextColumn::make('pickup_date')->sortable(),
                Tables\Columns\TextColumn::make('return_date')->sortable(),
                Tables\Columns\TextColumn::make('book.title')
                    ->url(fn(Transaction $record) => BookResource::getUrl('edit', ['record' => $record->book])),
                Tables\Columns\BadgeColumn::make('is_returned')
                    ->icons([
                        'heroicon-o-x-circle' => 0,
                        'heroicon-o-check-circle' => 1,
                    ])
                    ->colors([
                        'danger' => 0,
                        'success' => 1,
                    ])
                    ->enum([
                        0 => 'Not Returned',
                        1 => 'Returned'
                    ])->sortable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'DESC')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
