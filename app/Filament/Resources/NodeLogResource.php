<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NodeLogResource\Pages;
use App\Filament\Resources\NodeLogResource\RelationManagers;
use App\Models\NodeLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NodeLogResource extends Resource
{
    protected static ?string $model = NodeLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Node Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('node_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('rssi_dbm')
                    ->numeric(),
                Forms\Components\TextInput::make('snr_db')
                    ->numeric(),
                Forms\Components\TextInput::make('signal_quality')
                    ->maxLength(20),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\DateTimePicker::make('waktu'),
                Forms\Components\TextInput::make('type_sesi')
                    ->maxLength(64),
                Forms\Components\TextInput::make('sesi_id')
                    ->maxLength(64),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('node_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rssi_dbm')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('snr_db')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('signal_quality')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('waktu')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_sesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sesi_id')
                    ->searchable(),
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
            'index' => Pages\ListNodeLogs::route('/'),
            'create' => Pages\CreateNodeLog::route('/create'),
            'edit' => Pages\EditNodeLog::route('/{record}/edit'),
        ];
    }
}
