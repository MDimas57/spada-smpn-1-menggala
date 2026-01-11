<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelolaEskulResource\Pages;
use App\Filament\Resources\KelolaEskulResource\RelationManagers\SiswasRelationManager;
use App\Models\Eskul;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelolaEskulResource extends Resource
{
    protected static ?string $model = Eskul::class;

    protected static ?string $slug = 'kelola-eskul';
    protected static ?string $navigationLabel = 'Kelola Ekstrakurikuler';
    protected static ?string $pluralLabel = 'Kelola Ekstrakurikuler';
    protected static ?string $navigationGroup = 'Kesiswaan';
    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    /**
     * FILTER DATA BERDASARKAN ROLE:
     *  - Admin → semua data
     *  - Guru pembina → hanya eskul yang dibinanya
     *  - Guru lain → kosong
     */
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        // Admin → lihat semua
        if ($user->hasRole('admin')) {
            return parent::getEloquentQuery();
        }

        // Jika tidak punya relasi guru → jangan tampilkan apa pun
        if (!$user->guru) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        // Guru pembina → hanya lihat eskul yang dia bina
        return parent::getEloquentQuery()
            ->whereHas('pembinas', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            });
    }

    /**
     * IZIN AKSES HALAMAN LIST
     */
    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user->hasRole('admin') || $user->guru !== null;
    }

    /**
     * IZIN AKSES MELIHAT DETAIL
     */
    public static function canView($record): bool
    {
        $user = auth()->user();

        if ($user->hasRole('admin'))
            return true;
        if (!$user->guru)
            return false;

        return $record->pembinas()
            ->where('guru_id', $user->guru->id)
            ->exists();
    }

    /**
     * IZIN EDIT
     */
    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if ($user->hasRole('admin'))
            return true;
        if (!$user->guru)
            return false;

        return $record->pembinas()
            ->where('guru_id', $user->guru->id)
            ->exists();
    }

    public static function canDelete($record): bool
    {
        return false;
    }
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        // Admin tetap bisa lihat menu
        if ($user->hasRole('admin')) {
            return true;
        }

        // Guru pembina → bisa lihat
        if ($user->hasRole('guru') && $user->guru) {
            return \App\Models\Eskul::whereHas('pembinas', function ($q) use ($user) {
                $q->where('guru_id', $user->guru->id);
            })->exists();
        }

        // Guru yang tidak punya eskul = menu hilang
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Eskul')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Eskul')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('pembina_display')
                            ->label('Pembina')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                if (!$record)
                                    return '-';

                                if ($record->pembinas()->exists()) {
                                    return $record->pembinas->map(function ($guru) {
                                        return $guru->user->name ?? '-';
                                    })->join(', ');
                                }

                                return '-';
                            }),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Eskul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pembinas.user.name')
                    ->label('Pembina')
                    ->listWithLineBreaks()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('siswas_count')
                    ->counts('siswas')
                    ->label('Jumlah Anggota'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Kelola Anggota')
                    ->icon('heroicon-m-user-group'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SiswasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelolaEskuls::route('/'),
            'edit' => Pages\EditKelolaEskul::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
