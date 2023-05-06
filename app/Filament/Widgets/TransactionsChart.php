<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TransactionsChart extends LineChartWidget
{
    protected static ?string $heading = 'Transactions Finished Chart';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $firstYearTransactionData = Trend::query(Transaction::query()->where('is_returned', 1)->where('class', 1))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();


        $secondYearTransactionData = Trend::query(Transaction::query()->where('is_returned', 1)->where('class', 2))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();


        $thirdYearTransactionData = Trend::query(Transaction::query()->where('is_returned', 1)->where('class', 3))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => '1st Year',
                    'data' => $firstYearTransactionData->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#FFD93D'
                ],
                [
                    'label' => '2nd Year',
                    'data' => $secondYearTransactionData->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#FF8400'

                ],
                [
                    'label' => '3rd Year',
                    'data' => $thirdYearTransactionData->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#4F200D'

                ],
            ],
            'labels' => $firstYearTransactionData->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
