<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use App\Models\storage;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StorageResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StorageResource\RelationManagers;
use App\Filament\Resources\StorageResource\RelationManagers\SubStorageRelationManager;

class StorageResource extends Resource
{
    protected static ?string $model = storage::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase' ;
    protected static ?string $navigationGroup = 'Storage Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('storage_name')
                        ->label('Name'),
                    TextInput::make('storage_location')
                        ->label('Location'),
                    Textarea::make('storage_description')
                        ->label('Description')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('storage_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('storage_location')
                    ->label('Location')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('storage_description')
                    ->label('Description')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Created at')
                    ->date('d-M-Y')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->label('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->hidden(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            SubStorageRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStorages::route('/'),
            'create' => Pages\CreateStorage::route('/create'),
            'view' => Pages\ViewStorage::route('/{record}'),
            'edit' => Pages\EditStorage::route('/{record}/edit'),
        ];
    }    
}
