<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('image_url')
                    ->label('Image URL')
                    ->url()
                    ->required()
                    ->maxLength(2048)
                    ->columnSpanFull(),
                TextInput::make('alt_text')
                    ->label('Alt text')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Toggle::make('is_primary')
                    ->label('Primary image')
                    ->helperText('The main image shown in the catalog. Only one per product.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('image_url')
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Preview')
                    ->height(56),
                TextColumn::make('image_url')
                    ->label('URL')
                    ->limit(40)
                    ->url(fn ($record) => $record->image_url, true)
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('alt_text')
                    ->label('Alt text')
                    ->limit(30)
                    ->toggleable(),
                IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean(),
            ])
            ->defaultSort('is_primary', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
