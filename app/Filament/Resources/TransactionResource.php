<?php

namespace App\Filament\Resources;

use App\Enums\BookTransactionEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\ActionGroup;
use Filament\Pages\Actions\ViewAction;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                            Forms\Components\TextInput::make('email')->email()->required(),
                            Forms\Components\TextInput::make('nisn')->numeric()->required(),
                            Forms\Components\Select::make('class')->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                            ])->required()
                        ]),
                    Forms\Components\Wizard\Step::make('Book Selection')
                        ->schema([
                            Forms\Components\Select::make('book_id')
                                ->multiple()
                                ->relationship('books', 'isbn')
                                ->getOptionLabelFromRecordUsing(fn(Book $record) => "{$record->title} - {$record->isbn}")
                                ->placeholder('Select Book by ISBN'),
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
                                ->withoutSeconds()
                                ->required(),
                            Forms\Components\DateTimePicker::make('return_date')
                                ->autofocus()
                                ->displayFormat($format = 'F j, Y H:i:s')
                                ->firstDayOfWeek($day = 1)
                                ->format($format = 'Y-m-d H:i:s')
                                ->placeholder('Set pickup time')
                                ->weekStartsOnMonday()
                                ->weekStartsOnSunday()
                                ->withoutSeconds()
                                ->required(),
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
                Tables\Columns\TextColumn::make('pickup_date')->sortable(),
                Tables\Columns\BadgeColumn::make('actual_pickup_date')->sortable(),
                Tables\Columns\TextColumn::make('return_date')->sortable(),
                Tables\Columns\BadgeColumn::make('actual_return_date')->sortable(),
                Tables\Columns\BadgeColumn::make('is_approved')
                    ->icons([
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-exclamation-circle' => 'pending',
                        'heroicon-o-check-circle' => 'approved',
                    ])
                    ->colors([
                        'danger' => BookTransactionEnum::fromName('Rejected'),
                        'secondary' => BookTransactionEnum::fromName('Pending'),
                        'success' => BookTransactionEnum::fromName('Approved'),
                    ])
                    ->enum([
                        'rejected' => 'Rejected',
                        'pending' => 'Pending',
                        'approved' => 'Approved'
                    ]),
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // ** Approve Book ** //
                    Tables\Actions\Action::make('approveBook')
                        ->icon('heroicon-o-check')
                        ->action(function (Transaction $record, array $data) {
                            $record->update([
                                'is_approved' => 'approved'
                            ]);

                            Filament::notify('success', $record->full_name . ' request has been approved');

                        })
                        ->requiresConfirmation()
                        ->modalHeading('Approve book?')
                        ->modalButton('Yes, confirm')
                        ->color('success')
                        ->visible(fn(Transaction $record): bool => (bool)!$record->is_returned && $record->is_approved == BookTransactionEnum::fromName('Pending')),

                    // ** Reject Book ** //
                    Tables\Actions\Action::make('rejectBook')
                        ->icon('heroicon-o-x')
                        ->action(function (Transaction $record, array $data) {
                            $record->update([
                                'is_approved' => BookTransactionEnum::fromName('Rejected')
                            ]);

                            Filament::notify('success', $record->full_name . ' request has been rejected');
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Reject book?')
                        ->modalButton('Yes, reject')
                        ->color('danger')
                        ->visible(fn(Transaction $record): bool => (bool)!$record->is_returned && $record->is_approved == BookTransactionEnum::fromName('Pending')),

                    // ** Pickup Book ** //
                    Tables\Actions\Action::make('pickupBook')
                        ->icon('heroicon-o-inbox')
                        ->action(function (Transaction $record, array $data) {
                            $record->update([
                                'actual_pickup_date' => now()
                            ]);

                            Filament::notify('success', $record->full_name . ' request has been picked up');
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Confirm book pickup')
                        ->modalButton('Yes, confirm')
                        ->color('success')
                        ->visible(fn(Transaction $record): bool => (bool)!$record->is_returned && $record->is_approved == BookTransactionEnum::fromName('Approved') && (bool)!$record->actual_pickup_date),

                    // ** Return Book ** //
                    Tables\Actions\Action::make('returnBook')
                        ->icon('heroicon-o-bookmark')
                        ->action(function (Transaction $record, array $data) {
                            $record->update([
                                'actual_return_date' => now(),
                                'is_returned' => true
                            ]);

                            $books = $record->books;

                            foreach ($books as $book) {
                                $book->update([
                                    'stock' => $book->stock + 1
                                ]);

                            }

                            Filament::notify('success', 'Book successfully return');
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Confirm returns of book')
                        ->modalButton('Yes, confirm')
                        ->color('success')
                        ->visible(fn(Transaction $record): bool => (bool)!$record->is_returned && $record->is_approved == BookTransactionEnum::fromName('Approved') && (bool)$record->actual_pickup_date),

                    // ** Cancel Book ** //
                    Tables\Actions\Action::make('cancelBook')
                        ->icon('heroicon-o-x')
                        ->action(function (Transaction $record, array $data) {
                            $record->update([
                                'actual_return_date' => null,
                                'is_returned' => false
                            ]);

                            $books = $record->books;

                            foreach ($books as $book) {
                                $book->update([
                                    'stock' => $book->stock + 1
                                ]);

                            }

                            Filament::notify('danger', 'Book return canceled');
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Cancel the return of book?')
                        ->modalButton('Yes, confirm')
                        ->color('danger')
                        ->visible(fn(Transaction $record): bool => (bool)!$record->is_returned && $record->is_approved == BookTransactionEnum::fromName('Approved'))
                ])
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
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return self::getModel()::where('is_returned', 0)->count();
    }
}
