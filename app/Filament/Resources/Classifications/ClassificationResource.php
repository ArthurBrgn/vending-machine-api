<?php

declare(strict_types=1);

namespace App\Filament\Resources\Classifications;

use App\Filament\Resources\Classifications\Pages\CreateClassification;
use App\Filament\Resources\Classifications\Pages\EditClassification;
use App\Filament\Resources\Classifications\Pages\ListClassifications;
use App\Filament\Resources\Classifications\RelationManagers\ProductCategoriesRelationManager;
use App\Filament\Resources\Classifications\Schemas\ClassificationForm;
use App\Filament\Resources\Classifications\Tables\ClassificationsTable;
use App\Models\Classification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class ClassificationResource extends Resource
{
    protected static ?string $model = Classification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ClassificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassificationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductCategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassifications::route('/'),
            'create' => CreateClassification::route('/create'),
            'edit' => EditClassification::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
