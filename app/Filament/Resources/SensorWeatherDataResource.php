<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SensorWeatherDataResource\Pages;
use App\Filament\Resources\SensorWeatherDataResource\RelationManagers;
use App\Models\SensorWeatherData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SensorWeatherDataResource extends Resource
{
    protected static ?string $model = SensorWeatherData::class;

    protected static ?string $navigationIcon = 'heroicon-o-cloud';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Weather Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sesi_id_getdata')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('node_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('voltage')
                    ->numeric(),
                Forms\Components\TextInput::make('current')
                    ->numeric(),
                Forms\Components\TextInput::make('power')
                    ->numeric(),
                Forms\Components\TextInput::make('light')
                    ->numeric(),
                Forms\Components\TextInput::make('rain')
                    ->numeric(),
                Forms\Components\TextInput::make('rain_adc')
                    ->numeric(),
                Forms\Components\TextInput::make('wind')
                    ->numeric(),
                Forms\Components\TextInput::make('wind_pulse')
                    ->numeric(),
                Forms\Components\TextInput::make('humidity')
                    ->numeric(),
                Forms\Components\TextInput::make('temp_dht')
                    ->numeric(),
                Forms\Components\TextInput::make('ts_counter')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('received_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sesi_id_getdata')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('voltage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('power')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('light')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rain')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rain_adc')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wind')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wind_pulse')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('humidity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('temp_dht')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ts_counter')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('received_at')
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
            'index' => Pages\ListSensorWeatherData::route('/'),
            'create' => Pages\CreateSensorWeatherData::route('/create'),
            'edit' => Pages\EditSensorWeatherData::route('/{record}/edit'),
        ];
    }
}
