<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiUjianResource\Pages;
use App\Models\NilaiUjian;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class NilaiUjianResource extends Resource
{
    protected static ?string $model = NilaiUjian::class;
    protected static ?string $navigationGroup = 'Pengelolaan Nilai Siswa';
    protected static ?string $navigationLabel = 'Data Nilai (UTS/UAS)';
    protected static ?string $pluralLabel = 'Nilai Ujian';
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Nilai')
                    ->schema([
                        Forms\Components\Select::make('siswa_id')
                            ->relationship('siswa', 'nis')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nis} - {$record->user->name}")
                            ->searchable(['nis'])
                            ->preload()
                            ->required()
                            ->label('Siswa'),

                        Forms\Components\Select::make('mapel_id')
                            ->relationship('mapel', 'nama')
                            ->required()
                            ->preload()
                            ->label('Mata Pelajaran'),

                        Forms\Components\Select::make('jenis_ujian')
                            ->options([
                                'UTS' => 'UTS (Ujian Tengah Semester)',
                                'UAS' => 'UAS (Ujian Akhir Semester)',
                            ])
                            ->required()
                            ->label('Jenis Ujian'),

                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->relationship('tahunAjaran', 'tahun')
                            ->required()
                            ->label('Tahun Ajaran'),

                        Forms\Components\TextInput::make('nilai')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required()
                            ->label('Nilai (0-100)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ── No. Urut ───────────────────────────────────────────
                Tables\Columns\TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex()
                    ->width('52px')
                    ->alignCenter(),

                // ── NIS ────────────────────────────────────────────────
                Tables\Columns\TextColumn::make('siswa.nis')
                    ->label('NIS')
                    ->sortable()
                    ->searchable()
                    ->weight('medium')
                    ->copyable()
                    ->copyMessage('NIS disalin')
                    ->width('100px'),

                // ── Nama Siswa + Kelas sebagai deskripsi ───────────────
                Tables\Columns\TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable()
                    ->weight('semibold')
                    ->wrap(),

                // ── Kelas ──────────────────────────────────────────────
                Tables\Columns\TextColumn::make('siswa.kelas.nama')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                // ── Mapel sebagai badge ────────────────────────────────
                Tables\Columns\TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                // ── Jenis Ujian ────────────────────────────────────────
                Tables\Columns\TextColumn::make('jenis_ujian')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'UTS'   => 'info',
                        'UAS'   => 'warning',
                        default => 'gray',
                    })
                    ->alignCenter()
                    ->sortable(),

                // ── Tahun Ajaran ───────────────────────────────────────
                Tables\Columns\TextColumn::make('tahunAjaran.tahun')
                    ->label('T.A.')
                    ->sortable()
                    ->alignCenter()
                    ->color('gray'),

                // ── Nilai (inline editable) ────────────────────────────
                Tables\Columns\TextInputColumn::make('nilai')
                    ->label('Nilai')
                    ->type('number')
                    ->rules(['required', 'numeric', 'min:0', 'max:100'])
                    ->extraInputAttributes([
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                        'style' => 'width: 70px; text-align: center; font-weight: 700; font-size: 0.95rem; border-radius: 8px;',
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        if (!is_numeric($state) || $state < 0 || $state > 100) {
                            Notification::make()
                                ->title('Nilai tidak valid')
                                ->body('Masukkan angka antara 0 – 100.')
                                ->danger()
                                ->send();
                            return;
                        }
                        $record->update(['nilai' => (float) $state]);
                        Notification::make()
                            ->title('Nilai diperbarui')
                            ->body($record->siswa?->user?->name . " → {$state}")
                            ->success()
                            ->send();
                    })
                    ->sortable()
                    ->alignCenter(),

                // ── Grade otomatis (pakai key berbeda agar tidak bentrok) ─
                Tables\Columns\TextColumn::make('nilai_grade')
                    ->label('Grade')
                    ->alignCenter()
                    ->badge()
                    ->getStateUsing(fn ($record): string => match (true) {
                        $record->nilai >= 90 => 'A',
                        $record->nilai >= 80 => 'B',
                        $record->nilai >= 70 => 'C',
                        $record->nilai >= 60 => 'D',
                        default              => 'E',
                    })
                    ->color(fn ($record): string => match (true) {
                        $record->nilai >= 90 => 'success',
                        $record->nilai >= 80 => 'info',
                        $record->nilai >= 70 => 'warning',
                        $record->nilai >= 60 => 'primary',
                        default              => 'danger',
                    }),
            ])
            ->defaultSort('siswa.nis', 'asc')
            ->striped()
            ->paginated([15, 25, 50, 100])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->options(\App\Models\Kelas::orderBy('nama')->pluck('nama', 'id'))
                    ->query(fn (Builder $query, array $data) =>
                        $data['value']
                            ? $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $data['value']))
                            : $query
                    )
                    ->indicator('Kelas'),

                Tables\Filters\SelectFilter::make('mapel_id')
                    ->relationship('mapel', 'nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->preload()
                    ->indicator('Mapel'),

                Tables\Filters\SelectFilter::make('jenis_ujian')
                    ->options(['UTS' => 'UTS', 'UAS' => 'UAS'])
                    ->label('Jenis Ujian')
                    ->indicator('Jenis'),

                Tables\Filters\SelectFilter::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'tahun')
                    ->label('Tahun Ajaran')
                    ->preload()
                    ->indicator('T.A'),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter')
                    ->icon('heroicon-o-funnel'),
            )
            ->headerActions([

                // ── Import CSV ──────────────────────────────────────────
                Action::make('importCsv')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('jenis_ujian')
                            ->label('Jenis Ujian')
                            ->options([
                                'UTS' => 'UTS (Ujian Tengah Semester)',
                                'UAS' => 'UAS (Ujian Akhir Semester)',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('mapel_id')
                            ->label('Mata Pelajaran')
                            ->options(Mapel::pluck('nama', 'id'))
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->options(TahunAjaran::pluck('tahun', 'id'))
                            ->required()
                            ->native(false),

                        Forms\Components\FileUpload::make('csv_file')
                            ->label('File CSV')
                            ->acceptedFileTypes(['text/csv', 'application/csv', 'text/plain'])
                            ->required()
                            ->helperText('Format: NIS, Nilai (0–100). Baris pertama = header.'),
                    ])
                    ->action(function (array $data) {
                        try {
                            $filePath = is_array($data['csv_file'])
                                ? reset($data['csv_file'])
                                : $data['csv_file'];

                            $file = storage_path('app/public/' . $filePath);

                            if (!file_exists($file)) {
                                Notification::make()->title('File tidak ditemukan')->danger()->send();
                                return;
                            }

                            $rows   = array_map('str_getcsv', file($file));
                            $header = array_map('trim', $rows[0]);
                            unset($rows[0]);

                            $imported = 0;
                            $errors   = [];

                            DB::beginTransaction();

                            foreach ($rows as $index => $row) {
                                try {
                                    if (count($row) < 2) continue;

                                    $rowData  = array_combine($header, array_map('trim', $row));
                                    $nisKey   = collect(array_keys($rowData))->first(fn ($k) => strtolower($k) === 'nis');
                                    $nilaiKey = collect(array_keys($rowData))->first(fn ($k) => strtolower($k) === 'nilai');
                                    $nis      = $nisKey   ? $rowData[$nisKey]   : null;
                                    $nilai    = $nilaiKey ? $rowData[$nilaiKey] : null;

                                    if (empty($nis)) {
                                        $errors[] = "Baris " . ($index + 1) . ": NIS kosong";
                                        continue;
                                    }
                                    if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
                                        $errors[] = "Baris " . ($index + 1) . ": Nilai '{$nilai}' tidak valid";
                                        continue;
                                    }

                                    $siswa = Siswa::where('nis', $nis)->first();
                                    if (!$siswa) {
                                        $errors[] = "Baris " . ($index + 1) . ": NIS '{$nis}' tidak ditemukan";
                                        continue;
                                    }

                                    NilaiUjian::updateOrCreate(
                                        [
                                            'siswa_id'        => $siswa->id,
                                            'mapel_id'        => $data['mapel_id'],
                                            'jenis_ujian'     => $data['jenis_ujian'],
                                            'tahun_ajaran_id' => $data['tahun_ajaran_id'],
                                        ],
                                        ['nilai' => (float) $nilai]
                                    );

                                    $imported++;

                                } catch (\Exception $e) {
                                    $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
                                }
                            }

                            DB::commit();
                            @unlink($file);

                            if ($imported > 0) {
                                $msg = "Berhasil import {$imported} nilai";
                                if (count($errors)) $msg .= ", " . count($errors) . " baris error";
                                Notification::make()->title('Import Selesai')->body($msg)->success()->send();
                                if (count($errors)) {
                                    Notification::make()
                                        ->title('Detail Error')
                                        ->body(implode("\n", array_slice($errors, 0, 5)))
                                        ->warning()->send();
                                }
                            } else {
                                Notification::make()
                                    ->title('Tidak ada data terimport')
                                    ->body(count($errors) ? implode("\n", array_slice($errors, 0, 5)) : 'File kosong atau format tidak sesuai')
                                    ->warning()->send();
                            }

                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()->title('Import Gagal')->body($e->getMessage())->danger()->send();
                        }
                    })
                    ->modalWidth('lg'),

                // ── Download Template ───────────────────────────────────
                Action::make('downloadTemplate')
                    ->label('Template CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        $csv = "NIS,Nilai\n2024001,85\n2024002,90\n2024003,78\n";
                        return response()->streamDownload(
                            fn () => print($csv),
                            'template_nilai_ujian.csv',
                            ['Content-Type' => 'text/csv']
                        );
                    }),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-academic-cap')
            ->emptyStateHeading('Belum ada data nilai')
            ->emptyStateDescription('Tambah nilai secara manual atau import melalui CSV.');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['siswa.kelas', 'siswa.user', 'mapel', 'tahunAjaran']);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNilaiUjians::route('/'),
            'create' => Pages\CreateNilaiUjian::route('/create'),
            'edit'   => Pages\EditNilaiUjian::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }
}