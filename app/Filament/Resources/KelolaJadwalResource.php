<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelolaJadwalResource\Pages;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelolaJadwalResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Kelola Jadwal';
    protected static ?string $modelLabel = 'Jadwal Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'kelola-jadwal';

    // 🔒 Batasi query untuk guru hanya melihat kelas yang mereka ajar
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        // Jika user adalah guru, filter hanya kelas yang diajarnya
        if ($user && $user->hasRole('guru')) {
            $guru = $user->guru; // Asumsi relasi user->guru sudah ada

            if ($guru) {
                $query->whereHas('jadwals', function ($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->distinct();
            } else {
                // Jika tidak punya data guru, tidak tampilkan apapun
                $query->whereRaw('1 = 0');
            }
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Kelas')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Kelas')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Forms\Components\Section::make('Jadwal Pelajaran')
                    ->description('Tambahkan sesi pelajaran untuk kelas ini.')
                    ->schema([
                        Forms\Components\Repeater::make('jadwals')
                            ->relationship()
                            ->label('')
                            ->addActionLabel('Tambah Sesi Pelajaran')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('hari')
                                            ->options([
                                                'Senin' => 'Senin',
                                                'Selasa' => 'Selasa',
                                                'Rabu' => 'Rabu',
                                                'Kamis' => 'Kamis',
                                                'Jumat' => 'Jumat',
                                                'Sabtu' => 'Sabtu',
                                            ])
                                            ->required(),

                                        Forms\Components\TimePicker::make('jam_mulai')
                                            ->label('Mulai')
                                            ->seconds(false)
                                            ->required(),

                                        Forms\Components\TimePicker::make('jam_selesai')
                                            ->label('Selesai')
                                            ->seconds(false)
                                            ->required(),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('mapel_id')
                                            ->label('Mata Pelajaran')
                                            ->options(Mapel::all()->pluck('nama', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set) => $set('guru_id', null)),

                                        Forms\Components\Select::make('guru_id')
                                            ->label('Guru Pengampu')
                                            ->options(function (Get $get) {
                                                $mapelId = $get('mapel_id');

                                                if (!$mapelId) {
                                                    return [];
                                                }

                                                return Guru::whereHas('mapels', function ($q) use ($mapelId) {
                                                    $q->where('mapels.id', $mapelId);
                                                })->with('user')->get()->pluck('user.name', 'id');
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->placeholder(
                                                fn(Get $get) =>
                                                $get('mapel_id') ? 'Pilih Guru' : 'Pilih Mapel Terlebih Dahulu'
                                            ),
                                    ]),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->cloneable()
                            ->reorderableWithButtons(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jadwals_count')
                    ->counts('jadwals')
                    ->label('Total Sesi')
                    ->badge()
                    ->color('info')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Jadwal')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn($record) =>
                        static::getUrl('view', ['record' => $record])
                    ),

                Tables\Actions\EditAction::make()
                    ->label('Atur Jadwal')
                    ->icon('heroicon-m-calendar-days')
                    ->visible(fn() => auth()->user()?->hasRole('admin')), // 🔥 Hanya Admin yang bisa Edit
            ])
            ->bulkActions([]);
    }

    public static function getRecordView(): string
    {
        return 'filament.resources.kelola-jadwal.view';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelolaJadwals::route('/'),
            'view' => Pages\ViewKelolaJadwal::route('/{record}'),
            'edit' => Pages\EditKelolaJadwal::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
