<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\BooleanColumn;
//use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
//use App\Filament\Resources\UserResource\RelationManagers;
//use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('name')
                        ->placeholder('Enter your Name')
                        ->required()
                        ->maxLength(255),
                    Toggle::make('is_admin')
                        ->required(),
                    TextInput::make('email')
                        ->placeholder('Enter your Email Address ( ___@locator.com )')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                    TextInput::make('password')
                        ->label('Password')
                        ->placeholder('Enter your Password')
                        ->password()
                        ->maxLength(255)
                        ->dehydrateStateUsing(static fn (null|string $state): null|string =>
                            filled($state) ? Hash::make($state): null,
                        )->required(static fn (Page $livewire): bool =>
                            $livewire instanceof CreateUser,
                        )->dehydrated(static fn (null|string $state): bool =>
                            filled($state),
                        )->label(static fn (Page $livewire): string =>
                            ($livewire instanceof EditUser) ? 'New Password' : 'Password'
                        )
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->required(),
                    CheckboxList::make('roles')
                        ->relationship('roles', 'name')
                        ->helperText('ONLY CHOOSE ONE!')
                        ->required()
                ])
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('name')->sortable(),
                BooleanColumn::make('is_admin')->sortable(),
                TextColumn::make('roles.name')->sortable(),
                TextColumn::make('email')->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->color('warning'),
                DeleteAction::make()
                    ->label('Delete'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}