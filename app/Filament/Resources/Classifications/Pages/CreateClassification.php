<?php

declare(strict_types=1);

namespace App\Filament\Resources\Classifications\Pages;

use App\Filament\Resources\Classifications\ClassificationResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateClassification extends CreateRecord
{
    protected static string $resource = ClassificationResource::class;
}
