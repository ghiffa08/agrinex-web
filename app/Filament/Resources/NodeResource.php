<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NodeResource\Pages;
use App\Filament\Resources\NodeResource\RelationManagers;
use App\Models\Node;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Main';

    protected static ?string $navigationLabel = 'Sensor Nodes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('node_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('group')
                    ->maxLength(10),
                Forms\Components\TextInput::make('kode_perlakuan')
                    ->maxLength(20),
                Forms\Components\TextInput::make('lokasi')
                    ->maxLength(100),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('waktu_dibuat'),
                Forms\Components\DateTimePicker::make('waktu_update'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('node_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_perlakuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_dibuat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_update')
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
            'index' => Pages\ListNodes::route('/'),
            'create' => Pages\CreateNode::route('/create'),
            'edit' => Pages\EditNode::route('/{record}/edit'),
        ];
    }
}
