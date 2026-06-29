<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SensorNodeDataResource\Pages;
use App\Filament\Resources\SensorNodeDataResource\RelationManagers;
use App\Models\SensorNodeData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SensorNodeDataResource extends Resource
{
    protected static ?string $model = SensorNodeData::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Sensor Node Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sesi_id_getdata')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('node_id')
                    ->relationship('node', 'id')
                    ->required(),
                Forms\Components\TextInput::make('voltage_v')
                    ->numeric(),
                Forms\Components\TextInput::make('battery_pct')
                    ->numeric(),
                Forms\Components\TextInput::make('current_ma')
                    ->numeric(),
                Forms\Components\TextInput::make('power_mw')
                    ->numeric(),
                Forms\Components\TextInput::make('flow_rate')
                    ->numeric(),
                Forms\Components\TextInput::make('total_volume_l')
                    ->numeric(),
                Forms\Components\TextInput::make('temp_c')
                    ->numeric(),
                Forms\Components\TextInput::make('soil_pct')
                    ->numeric(),
                Forms\Components\TextInput::make('soil_adc')
                    ->numeric(),
                Forms\Components\TextInput::make('ai_valve_decision')
                    ->maxLength(16),
                Forms\Components\TextInput::make('adaptive_sleep_duration')
                    ->numeric(),
                Forms\Components\TextInput::make('rssi')
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
                Tables\Columns\TextColumn::make('node.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('voltage_v')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('battery_pct')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_ma')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('power_mw')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('flow_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_volume_l')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('temp_c')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soil_pct')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('soil_adc')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ai_valve_decision')
                    ->searchable(),
                Tables\Columns\TextColumn::make('adaptive_sleep_duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rssi')
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
            'index' => Pages\ListSensorNodeData::route('/'),
            'create' => Pages\CreateSensorNodeData::route('/create'),
            'edit' => Pages\EditSensorNodeData::route('/{record}/edit'),
        ];
    }
}
