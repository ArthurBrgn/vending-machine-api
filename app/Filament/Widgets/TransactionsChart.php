<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class TransactionsChart extends ChartWidget
{
    protected ?string $heading = 'Transactions chart';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        switch ($activeFilter) {
            case 'week':
                $data = Trend::model(Transaction::class)
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
                    ->count();
                break;

            case 'month':
                $data = Trend::model(Transaction::class)
                    ->between(
                        start: now()->startOfMonth(),
                        end: now()->endOfMonth(),
                    )
                    ->perDay()
                    ->count();
                break;

            case 'year':
                $data = Trend::model(Transaction::class)
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->count();
                break;

            default:
                $data = Trend::model(Transaction::class)
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
                    ->count();
                break;
        }

        return [
            'datasets' => [
                [
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) {
                if ($this->filter === 'year') {
                    $dateObject = Carbon::createFromFormat('Y-m', $value->date);

                    return $dateObject->format('M');
                }

                return $value->date;
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    public function getDescription(): ?string
    {
        return 'The number of transactions processed during the selected period.';
    }

	protected function getOptions(): array
	{
		return [
			'plugins' => [
				'legend' => [
					'display' => false,
				],
			],
		];
	}
}
