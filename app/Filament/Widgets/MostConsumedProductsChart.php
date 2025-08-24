<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class MostConsumedProductsChart extends ChartWidget
{
    protected ?string $heading = 'Most consumed products chart';

    protected static ?int $sort = 0;

    protected function getData(): array
    {
        $consumptionData = Transaction::select('product_categories.name', DB::raw('COUNT(transactions.id) as total'))
            ->join('product_categories', 'transactions.product_category_id', '=', 'product_categories.id')
            ->groupBy('product_categories.name')
            ->limit(20)
            ->get();

        return [
            'labels' => $consumptionData->pluck('name'),
            'datasets' => [
                [
                    'data' => $consumptionData->pluck('total'),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
