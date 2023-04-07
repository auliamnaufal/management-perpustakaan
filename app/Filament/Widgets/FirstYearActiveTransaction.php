<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class FirstYearActiveTransaction extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make(
                '1st year active transaction',
                Transaction::query()->where('is_returned', 0)->where('class', 1)->count()
            ),
            Card::make(
                '2nd year active transaction',
                Transaction::query()->where('is_returned', 0)->where('class', 2)->count()
            ),
            Card::make(
                '3rd year active transaction',
                Transaction::query()->where('is_returned', 0)->where('class', 3)->count()
            ),
        ];
    }
}
