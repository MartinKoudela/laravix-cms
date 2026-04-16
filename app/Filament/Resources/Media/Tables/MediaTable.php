<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('path')
                        ->disk('public')
                        ->imageSize(56)
                        ->square()
                        ->extraImgAttributes(['loading' => 'lazy'])
                        ->defaultImageUrl(fn ($record): string => 'https://ui-avatars.com/api/?name='.urlencode(pathinfo($record->name ?? 'file', PATHINFO_EXTENSION)).'&size=56&background=e2e8f0&color=64748b&bold=true&length=3')
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('name')
                            ->weight(FontWeight::Medium)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('mime_type')
                            ->badge()
                            ->color('gray')
                            ->searchable(),
                    ]),
                    Stack::make([
                        TextColumn::make('size')
                            ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB')
                            ->color('gray')
                            ->sortable(),
                        TextColumn::make('site.name')
                            ->color('gray')
                            ->sortable(),
                    ])->visibleFrom('md'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('site')
                    ->relationship('site', 'name'),
                SelectFilter::make('type')
                    ->label('File type')
                    ->options([
                        'image' => 'Images',
                        'other' => 'Other files',
                    ])
                    ->query(function ($query, array $data): void {
                        if ($data['value'] === 'image') {
                            $query->where('mime_type', 'like', 'image/%');
                        } elseif ($data['value'] === 'other') {
                            $query->where('mime_type', 'not like', 'image/%');
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
