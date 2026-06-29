<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JsonBackupResource\Pages;
use App\Filament\Resources\JsonBackupResource\RelationManagers;
use App\Models\JsonBackup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JsonBackupResource extends Resource
{
    protected static ?string $model = JsonBackup::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'JSON Backup';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sesi_id_getdata')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('json_data')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('data_size_kb')
                    ->numeric(),
                Forms\Components\TextInput::make('total_records')
                    ->numeric(),
                Forms\Components\TextInput::make('node_completeness')
                    ->maxLength(20),
                Forms\Components\TextInput::make('getdata_logs_count')
                    ->numeric(),
                Forms\Components\TextInput::make('sensor_weather_count')
                    ->numeric(),
                Forms\Components\TextInput::make('sensor_node_count')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('backup_timestamp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sesi_id_getdata')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_size_kb')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_records')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node_completeness')
                    ->searchable(),
                Tables\Columns\TextColumn::make('getdata_logs_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sensor_weather_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sensor_node_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('backup_timestamp')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListJsonBackups::route('/'),
            'create' => Pages\CreateJsonBackup::route('/create'),
            'edit' => Pages\EditJsonBackup::route('/{record}/edit'),
        ];
    }
}
