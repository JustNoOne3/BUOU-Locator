<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use App\Models\sub_storage;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubStorageResource\Pages;
use App\Filament\Resources\SubStorageResource\RelationManagers;
use App\Filament\Resources\SubStorageResource\RelationManagers\FileRelationManager;

class SubStorageResource extends Resource
{
    protected static ?string $model = sub_storage::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Storage Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('storage_id')
                        ->label('Storage')
                        ->relationship('storage', 'storage_name'),
                    TextInput::make('substorage_name')
                        ->label('Name'),
                    Textarea::make('substorage_description')
                        ->label('Description')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('substorage_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('substorage_description')
                    ->label('Description')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Created at')
                    ->date('d-M-Y')
                    ->sortable(),
                TextColumn::make('storage.storage_name')
                    ->label('Storage')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('success')
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
            FileRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubStorages::route('/'),
            'create' => Pages\CreateSubStorage::route('/create'),
            'view' => Pages\ViewSubStorage::route('/{record}'),
            'edit' => Pages\EditSubStorage::route('/{record}/edit'),
        ];
    }    
}
