<?php

namespace App\Filament\Resources\StorageResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SubStorageRelationManager extends RelationManager
{
    protected static string $relationship = 'sub_storage';

    protected static ?string $recordTitleAttribute = 'substorage_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('substorage_name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('substorage_description')
                    ->label('Description'),
                DatePicker::make('created_at')
                    ->label('Created at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('substorage_name')
                    ->label('Name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make()
                //    ->label('New Sub Storage'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('success')
                    ->label(''),
                //Tables\Actions\EditAction::make()
                //    ->label(''),
                //Tables\Actions\DeleteAction::make()
                //    ->label(''),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
