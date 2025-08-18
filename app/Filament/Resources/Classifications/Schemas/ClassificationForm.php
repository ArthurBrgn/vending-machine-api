<?php

declare(strict_types=1);

namespace App\Filament\Resources\Classifications\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ClassificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('daily_points_limit')
                    ->required()
                    ->numeric()
                    ->minValue(50),
            ]);
    }
}
