<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IrrigateLogResource\Pages;
use App\Filament\Resources\IrrigateLogResource\RelationManagers;
use App\Models\IrrigateLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IrrigateLogResource extends Resource
{
    protected static ?string $model = IrrigateLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Irrigate Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sesi_id_irrigate')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('waktu_mulai')
                    ->required(),
                Forms\Components\DateTimePicker::make('waktu_akhir'),
                Forms\Components\TextInput::make('node_sukses')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('node_gagal')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('valve_on_akhir')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sesi_id_irrigate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_akhir')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node_sukses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node_gagal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valve_on_akhir')
                    ->numeric()
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
            'index' => Pages\ListIrrigateLogs::route('/'),
            'create' => Pages\CreateIrrigateLog::route('/create'),
            'edit' => Pages\EditIrrigateLog::route('/{record}/edit'),
        ];
    }
}
