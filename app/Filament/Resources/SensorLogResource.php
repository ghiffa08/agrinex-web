<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SensorLogResource\Pages;
use App\Filament\Resources\SensorLogResource\RelationManagers;
use App\Models\SensorLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SensorLogResource extends Resource
{
    protected static ?string $model = SensorLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Sensor Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->relationship('device', 'name')
                    ->required(),
                Forms\Components\TextInput::make('temperature')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('humidity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('soil_moisture')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('battery_level')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('temperature')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('humidity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soil_moisture')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('battery_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListSensorLogs::route('/'),
            'create' => Pages\CreateSensorLog::route('/create'),
            'edit' => Pages\EditSensorLog::route('/{record}/edit'),
        ];
    }
}
