<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValveLogResource\Pages;
use App\Filament\Resources\ValveLogResource\RelationManagers;
use App\Models\ValveLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ValveLogResource extends Resource
{
    protected static ?string $model = ValveLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Valve Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('node_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sesi_id_irrigate')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('durasi_detik')
                    ->numeric(),
                Forms\Components\TextInput::make('volume_air')
                    ->numeric(),
                Forms\Components\TextInput::make('rata_rata')
                    ->numeric(),
                Forms\Components\TextInput::make('pulse')
                    ->numeric(),
                Forms\Components\TextInput::make('status'),
                Forms\Components\DateTimePicker::make('waktu')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('node_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sesi_id_irrigate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('durasi_detik')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume_air')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rata_rata')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pulse')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('waktu')
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
            'index' => Pages\ListValveLogs::route('/'),
            'create' => Pages\CreateValveLog::route('/create'),
            'edit' => Pages\EditValveLog::route('/{record}/edit'),
        ];
    }
}
