<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Kelola Siswa';
    protected static ?string $pluralLabel = 'Kelola Siswa';
    protected static ?string $modelLabel = 'Kelola Siswa';
    protected static ?int $navigationSort = 11; // ✅ DIUBAH

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Akun Siswa')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Siswa')
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->rule(function ($record) {
                                $userId = $record?->user_id;
                                return Rule::unique('users', 'email')->ignore($userId);
                            }),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])->columns(2),

                Forms\Components\Section::make('Data Akademik')
                    ->schema([
                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('kelas_id')
                            ->relationship('kelas', 'nama')
                            ->searchable()
                            ->preload()
                            ->label('Kelas')
                            ->placeholder('Pilih Kelas (Opsional)')
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->placeholder('Belum Masuk Kelas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email'),
            ])
               ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                     ->before(fn (Siswa $record) => $record->user?->delete()),
            ])
            ->headerActions([
                Action::make('importCsv')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('File CSV')
                            ->acceptedFileTypes(['text/csv', 'application/csv', 'text/plain'])
                            ->required()
                            ->helperText('Upload file CSV dengan kolom: nama, email (opsional), nis (opsional), kelas (opsional)'),

                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas Default (Opsional)')
                            ->relationship('kelas', 'nama')
                            ->searchable()
                            ->preload()
                            ->helperText('Kelas default jika kolom kelas di CSV kosong'),

                        Forms\Components\Toggle::make('auto_generate_email')
                            ->label('Generate Email Otomatis')
                            ->helperText('Jika aktif, email akan dibuat otomatis dari nama (contoh: ahmad.faisal@sekolah.id)')
                            ->default(true)
                            ->reactive(),

                        Forms\Components\TextInput::make('email_domain')
                            ->label('Domain Email')
                            ->default('sekolah.id')
                            ->required()
                            ->visible(fn ($get) => $get('auto_generate_email'))
                            ->helperText('Domain yang akan digunakan untuk email otomatis'),

                        Forms\Components\TextInput::make('default_password')
                            ->label('Password Default')
                            ->default('password123')
                            ->required()
                            ->minLength(8)
                            ->helperText('Password yang akan digunakan untuk semua akun siswa yang diimport'),
                    ])
                    ->action(function (array $data) {
                        try {
                            $file = storage_path('app/public/' . $data['csv_file']);

                            if (!file_exists($file)) {
                                Notification::make()
                                    ->title('File tidak ditemukan')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $csv = array_map('str_getcsv', file($file));
                            $header = array_map('trim', $csv[0]);
                            unset($csv[0]);

                            $imported = 0;
                            $errors = [];

                            DB::beginTransaction();

                            foreach ($csv as $index => $row) {
                                try {
                                    if (count($row) < 1) continue;

                                    $rowData = array_combine($header, $row);

                                    if (empty(trim($rowData['nama']))) {
                                        $errors[] = "Baris " . ($index + 1) . ": Nama tidak boleh kosong";
                                        continue;
                                    }

                                    $nama = trim($rowData['nama']);

                                    // Generate atau gunakan email dari CSV
                                    if ($data['auto_generate_email'] || empty(trim($rowData['email'] ?? ''))) {
                                        $emailName = Str::slug(str_replace([',', '.'], '', $nama), '.');
                                        $email = $emailName . '@' . $data['email_domain'];

                                        $counter = 1;
                                        $originalEmail = $email;
                                        while (User::where('email', $email)->exists()) {
                                            $email = str_replace('@', $counter . '@', $originalEmail);
                                            $counter++;
                                        }
                                    } else {
                                        $email = trim($rowData['email']);
                                        if (User::where('email', $email)->exists()) {
                                            $errors[] = "Baris " . ($index + 1) . ": Email {$email} sudah terdaftar";
                                            continue;
                                        }
                                    }

                                    // Validasi NIS jika ada
                                    $nis = !empty($rowData['nis']) ? trim($rowData['nis']) : null;
                                    if ($nis && Siswa::where('nis', $nis)->exists()) {
                                        $errors[] = "Baris " . ($index + 1) . ": NIS {$nis} sudah terdaftar";
                                        continue;
                                    }

                                    // Tentukan kelas
                                    $kelasId = null;
                                    if (!empty($rowData['kelas'])) {
                                        $kelas = \App\Models\Kelas::where('nama', trim($rowData['kelas']))->first();
                                        if ($kelas) {
                                            $kelasId = $kelas->id;
                                        } else {
                                            $errors[] = "Baris " . ($index + 1) . ": Kelas '" . trim($rowData['kelas']) . "' tidak ditemukan";
                                        }
                                    } elseif (!empty($data['kelas_id'])) {
                                        $kelasId = $data['kelas_id'];
                                    }

                                    // Buat user
                                    $user = User::create([
                                        'name'     => $nama,
                                        'email'    => $email,
                                        'password' => Hash::make($data['default_password']),
                                    ]);

                                    $user->assignRole('siswa');

                                    Siswa::create([
                                        'user_id'  => $user->id,
                                        'nis'      => $nis,
                                        'kelas_id' => $kelasId,
                                    ]);

                                    $imported++;

                                } catch (\Exception $e) {
                                    $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
                                }
                            }

                            DB::commit();

                            @unlink($file);

                            if ($imported > 0) {
                                $message = "Berhasil mengimport {$imported} siswa";
                                if (count($errors) > 0) {
                                    $message .= " dengan " . count($errors) . " error";
                                }

                                Notification::make()
                                    ->title('Import Selesai')
                                    ->body($message)
                                    ->success()
                                    ->send();

                                if (count($errors) > 0) {
                                    Notification::make()
                                        ->title('Detail Error')
                                        ->body(implode("\n", array_slice($errors, 0, 5)))
                                        ->warning()
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->title('Tidak ada data yang diimport')
                                    ->body(count($errors) > 0 ? implode("\n", $errors) : 'File CSV kosong atau format tidak sesuai')
                                    ->warning()
                                    ->send();
                            }

                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->title('Import Gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalWidth('lg'),

                Action::make('downloadTemplate')
                    ->label('Download Template CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function () {
                        $csv  = "nama,email,nis,kelas\n";
                        $csv .= "Ahmad Faisal,ahmad.faisal@sekolah.id,2024001,X1\n";
                        $csv .= "Siti Rahmawati,,2024002,X2\n";
                        $csv .= "Budi Santoso,budi@sekolah.id,,\n";

                        return response()->streamDownload(function () use ($csv) {
                            echo $csv;
                        }, 'template_import_siswa.csv', [
                            'Content-Type' => 'text/csv',
                        ]);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
