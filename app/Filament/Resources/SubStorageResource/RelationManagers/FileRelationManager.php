<?php

namespace App\Filament\Resources\SubStorageResource\RelationManagers;

use App\Models\storage;
use App\Models\sub_storage;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FileRelationManager extends RelationManager
{
    protected static string $relationship = 'file';

    protected static ?string $recordTitleAttribute = 'file_name';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Tabs::make('Heading')
            ->tabs([
                Tabs\Tab::make('File')
                    ->schema([
                        TextInput::make('file_code')
                            ->required()
                            ->label('Code'),
                        TextInput::make('file_name')
                            ->required()
                            ->label('File Name'),
                        TextArea::make('file_description')
                            ->label('Description'),
                        TextInput::make('file_source')
                            ->label('Source'),
                        TextInput::make('file_destination')
                            ->nullable()
                            ->label('Destination'),
                        DatePicker::make('file_receivedDate')
                            ->label('Received Date'),
                        DatePicker::make('file_releasedDate')
                            ->nullable()
                            ->label('Released Date'),
                        TextInput::make('file_receivedBy')
                            ->label('Received By'),
                        Select::make('file_status')
                            ->label('Status')
                            ->options([
                                'Ingoing' => 'Ingoing',
                                'Outgoing' => 'Outgoing',
                            ]),
                        Select::make('storage_id')
                            ->label('Storage')
                            ->options(storage::all()->pluck('storage_name', 'id')->toArray())
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('sub_storage_id', null)),
                        Select::make('sub_storage_id')
                            ->label('Sub Storage')
                            ->options(function (callable $get){
                                $storage = storage::find($get('storage_id'));
                                if(!$storage){
                                    return sub_storage::all()->pluck('substorage_name', 'id');
                                }
                                return $storage->sub_storage->pluck('substorage_name', 'id'); 
                            }),
                    ]),
                Tabs\Tab::make('Upload')
                    ->schema([
                        FileUpload::make('file_images')
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                $fileName = $file->hashName();
                                $name = explode('.', $fileName);
                                return (string) str('fileImages/'.$name[0].'.png');
                            })
                            ->label('Files')
                            ->maxSize(5000)
                            ->image()
                            ->nullable()
                            ->multiple()
                            ->enableDownload()
                            ->enableOpen()
                            ->enableReordering(),
                        TextInput::make('file_link')
                            ->nullable()
                            ->label('File Link')
                            ->placeholder('Enter Drive Link')
                            ->url(),  
                    ])
                
            ])
            ->columns([
                'sm' => 2,
            ])
            ->columnSpan([
                'sm' => 2,
            ])
            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_code')
                    ->label('Code'),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make()
                //    ->label('New File'),
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
