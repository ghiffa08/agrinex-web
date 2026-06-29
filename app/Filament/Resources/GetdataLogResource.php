<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GetdataLogResource\Pages;
use App\Filament\Resources\GetdataLogResource\RelationManagers;
use App\Models\GetdataLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GetdataLogResource extends Resource
{
    protected static ?string $model = GetdataLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Getdata Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sesi_id_getdata')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('waktu_mulai')
                    ->required(),
                Forms\Components\DateTimePicker::make('waktu_selesai'),
                Forms\Components\TextInput::make('node_sukses')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('node_gagal')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sesi_id_getdata')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node_sukses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node_gagal')
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
            'index' => Pages\ListGetdataLogs::route('/'),
            'create' => Pages\CreateGetdataLog::route('/create'),
            'edit' => Pages\EditGetdataLog::route('/{record}/edit'),
        ];
    }
}
