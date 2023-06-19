<?php

namespace App\Filament\Resources;


use Filament\Forms;
use App\Models\File;
use Filament\Tables;
use App\Models\storage;
use App\Models\sub_storage;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Tabs;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\FileResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FileResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;


class FileResource extends Resource
{
    protected static ?string $model = file::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'View Files';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Tabs::make('Heading')
            ->tabs([
                Tabs\Tab::make('File')
                    ->schema([
                        TextInput::make('file_code')
                            ->placeholder('Enter File Code')
                            ->required()
                            ->label('Code'),
                        TextInput::make('file_name')
                            ->placeholder('Enter File Name')
                            ->required()
                            ->label('File Name'),
                        TextArea::make('file_description')
                            ->placeholder('Enter Description')
                            ->label('Description'),
                        TextInput::make('file_source')
                            ->placeholder('Enter File Source')
                            ->label('Source'),
                        TextInput::make('file_destination')
                            ->placeholder('Enter File Destination')
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
                                'Archieve' => 'Archieve',
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
                            ->label('Scanned Copy (image formats)')
                            ->maxSize(5000)
                            ->image()
                            ->nullable()
                            ->multiple()
                            ->enableDownload()
                            ->enableOpen()
                            ->enableReordering(),
                        SpatieMediaLibraryFileUpload::make('documents')
                            ->label('Attach Documents (docx/pdf)')
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                $fileName = $file->hashName();
                                $name = explode('.', $fileName);
                                return (string) str('files/'.$name[0].'.'.$name[1]);
                            })
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->multiple()
                            ->enableDownload()
                            ->enableReordering()
                            ->enableOpen(),
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
                TextColumn::make('file_code')
                    ->label('Code')
                    ->searchable(),
                TextColumn::make('file_name')
                    ->label('Name')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('file_description')
                    ->label('Description')
                    ->wrap(),
                TextColumn::make('file_receivedDate')
                    ->label('Received Date')
                    ->date('d-M-Y')
                    ->sortable(),        
                TextColumn::make('file_status')
                    ->label('Status')
                    ->color('primary'),
                TextColumn::make('storage.storage_name')
                    ->label('Storage')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('storage.storage_location')
                    ->label('Location')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('location')->relationship('storage', 'storage_location'),
                SelectFilter::make('storage')->relationship('storage', 'storage_name'),
                SelectFilter::make('sub_storage')->relationship('sub_storage', 'substorage_name'),
                SelectFilter::make('file_status')
                    ->options([
                        'Ingoing' => 'Ingoing',
                        'Outgoing' => 'Outgoing',
                        'Archieve' => 'Archieve',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('success')
                    ->label('View'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->label('Delete'),
            ])
            ->headerActions([ 
                FilamentExportHeaderAction::make('export')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export'),
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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'view' => Pages\ViewFile::route('/{record}'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }    
}
