<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Machine;
use App\Models\Transaction;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active employees', Employee::query()->where('is_active', true)->count())
                ->icon(Heroicon::User),
            Stat::make('Active machines', Machine::query()->where('is_active', true)->count())
                ->icon(Heroicon::ServerStack),
            Stat::make('Transactions today', Transaction::query()->whereDay('created_at', today())->count())
                ->icon(Heroicon::CurrencyDollar),
        ];
    }
}
