<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SheetMusicResource\Pages;
use App\Filament\Resources\SheetMusicResource\RelationManagers;
use App\Models\SheetMusic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SheetMusicResource extends Resource
{
    protected static ?string $model = SheetMusic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('composer')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('arranger')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('pdf_file_path')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\FileUpload::make('cover_image_path')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('composer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('arranger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pdf_file_path')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image_path'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListSheetMusic::route('/'),
            'create' => Pages\CreateSheetMusic::route('/create'),
            'edit' => Pages\EditSheetMusic::route('/{record}/edit'),
        ];
    }
}
