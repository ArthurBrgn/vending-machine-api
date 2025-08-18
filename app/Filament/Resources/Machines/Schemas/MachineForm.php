<?php

declare(strict_types=1);

namespace App\Filament\Resources\Machines\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class MachineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                TextInput::make('ip_address')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
