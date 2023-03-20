<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Pages\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Webbingbrasil\FilamentAdvancedFilter\Filters\BooleanFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Book Transaction';

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
                Tables\Columns\TextColumn::make('full_name')->searchable(),
                Tables\Columns\TextColumn::make('class')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('book.title')
                    ->url(fn(Transaction $record) => BookResource::getUrl('edit', ['record' => $record->book]))->searchable(),
                Tables\Columns\TextColumn::make('pickup_date')->sortable(),
                Tables\Columns\TextColumn::make('return_date')->sortable(),
                Tables\Columns\TextColumn::make('actual_return_date')->sortable(),
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
                    ]),
                Tables\Columns\TextColumn::make('nisn')
                    ->copyable()
                    ->copyMessage('Transaction NISN number copied')
                    ->copyMessageDuration(1500)->searchable()
            ])
            ->filters([
                BooleanFilter::make('is_returned')->default(false),
                DateFilter::make('pickup_date'),
                DateFilter::make('return_date'),
            ])
            ->defaultSort('created_at', 'DESC')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('returnBook')
                    ->icon('heroicon-o-bookmark')
                    ->action(function (Transaction $record, array $data) {
                        $record->update([
                            'actual_return_date' => now(),
                            'is_returned' => true
                        ]);

                        $record->book()->update([
                            'stock' => $record->book->stock + 1
                        ]);

                        Filament::notify('success', 'Book successfully return');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Confirm returns of book')
                    ->modalButton('Yes, confirm')
                    ->color('success')
                    ->visible(fn(Transaction $record): bool => (bool)!$record->is_returned),
                Tables\Actions\Action::make('cancelBook')
                    ->icon('heroicon-o-x')
                    ->action(function (Transaction $record, array $data) {
                        $record->update([
                            'actual_return_date' => null,
                            'is_returned' => false
                        ]);

                        $record->book()->update([
                            'stock' => $record->book->stock - 1
                        ]);

                        Filament::notify('success', 'Book return successfully canceled');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cancel the return of book?')
                    ->modalButton('Yes, confirm')
                    ->color('danger')
                    ->visible(fn(Transaction $record): bool => (bool)$record->is_returned)
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                ExportAction::make('export')
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

    protected static function getNavigationBadge(): ?string
    {
        return self::getModel()::where('is_returned', 0)->count();
    }
}
