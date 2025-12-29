<?php

namespace App\Filament\Resources\Notes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('judul_note')
                    ->searchable()
                    ->sortable()
                    ->label('Judul Catatan'),
                
                TextColumn::make('isi')
                    ->limit(50) 
                    ->label('Isi Ringkas')
                    // >>> SOLUSI: TAMBAHKAN .html() DI SINI <<<
                    ->html(), 
                
                IconColumn::make('lokasi') 
                    ->boolean()
                    ->label('Lokasi Aktif'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}