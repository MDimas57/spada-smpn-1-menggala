<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelolaKelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers\SiswasRelationManager;
use App\Models\Kelas;
use App\Models\WaliKelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelolaKelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationLabel = 'Kelola Kelas';
    protected static ?string $slug = 'kelola-kelas';
    protected static ?string $modelLabel = 'Kelola Kelas';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    /**
     * PEMBATASAN AKSES KE RESOURCE
     * - Hanya guru yang menjadi Wali Kelas yang bisa melihat menu ini
     * - Admin tidak bisa mengakses resource ini
     */
    public static function canViewAny(): bool
    {
        $user = auth()->user();

        // Pastikan user login
        if (!$user) {
            return false;
        }

        // Hanya user dengan role 'guru' yang bisa melihat menu ini
        if (!$user->hasRole('guru')) {
            return false;
        }

        // Cek apakah guru ini memiliki penugasan Wali Kelas
        $guru = $user->guru;
        if (!$guru) {
            return false;
        }

        // Jika guru sudah ditugaskan sebagai Wali Kelas, izinkan akses
        return WaliKelas::where('guru_id', $guru->id)->exists();
    }

    /**
     * QUERY FILTERING
     * Guru hanya bisa melihat kelas yang sudah ditugaskan sebagai Wali Kelasnya
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelas')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Kelas')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('wali_kelas_display')
                            ->label('Wali Kelas')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                if (!$record) return null;
                                return $record->waliKelas?->guru?->user?->name ?? 'Belum ditentukan';
                            }),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Filter: Hanya tampilkan kelas yang ditugaskan ke Wali Kelas (guru yang login)
            ->modifyQueryUsing(fn (Builder $query) =>
                $query
                    ->with(['waliKelas.guru.user'])
                    ->whereHas('waliKelas', function (Builder $subQuery) {
                        $guru = auth()->user()->guru;
                        $subQuery->where('guru_id', $guru->id);
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('waliKelas.guru.user.name')
                    ->label('Wali Kelas')
                    ->placeholder('Belum ada')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('siswas_count')
                    ->counts('siswas')
                    ->label('Jumlah Siswa')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Kelola Siswa')
                    ->icon('heroicon-m-user-group'),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListKelolaKelas::route('/'),
            'edit' => Pages\EditKelolaKelas::route('/{record}/edit'),
        ];
    }

    /**
     * Pastikan guru tidak bisa membuat kelas baru dari halaman ini
     */
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * PEMBATASAN EDIT
     * Guru hanya bisa mengedit kelas yang sudah ditugaskan kepadanya
     */
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('guru')) {
            return false;
        }

        $guru = $user->guru;
        if (!$guru) {
            return false;
        }

        // Cek apakah kelas ini ditugaskan ke guru yang login
        return WaliKelas::where('guru_id', $guru->id)
            ->where('kelas_id', $record->id)
            ->exists();
    }

    /**
     * PEMBATASAN DELETE
     * Guru tidak diizinkan menghapus kelas
     */
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
