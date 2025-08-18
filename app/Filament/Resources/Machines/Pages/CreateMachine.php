<?php

declare(strict_types=1);

namespace App\Filament\Resources\Machines\Pages;

use App\Filament\Resources\Machines\MachineResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateMachine extends CreateRecord
{
    protected static string $resource = MachineResource::class;
}
