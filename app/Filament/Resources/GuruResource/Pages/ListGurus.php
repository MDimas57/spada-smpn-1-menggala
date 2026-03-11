<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use App\Models\Guru;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ListGurus extends ListRecords
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(asset('templates/template_guru.xlsx'))
                ->openUrlInNewTab(),

            Action::make('importGuru')
                ->label('Import Excel/CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel atau CSV')
                        ->disk('public')
                        ->directory('import-tmp')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                        ])
                        ->maxSize(5120)
                        ->required()
                        ->helperText('Upload file .xlsx atau .csv sesuai template'),

                    Toggle::make('auto_generate_email')
                        ->label('Generate Email Otomatis')
                        ->helperText('Jika aktif, email dibuat otomatis dari nama jika kolom email kosong')
                        ->default(true)
                        ->reactive(),

                    TextInput::make('email_domain')
                        ->label('Domain Email')
                        ->default('sekolah.id')
                        ->required()
                        ->visible(fn ($get) => $get('auto_generate_email'))
                        ->helperText('Domain untuk email otomatis'),

                    TextInput::make('default_password')
                        ->label('Password Default')
                        ->default('password123')
                        ->required()
                        ->minLength(8)
                        ->helperText('Password yang akan digunakan untuk semua akun guru yang diimport'),
                ])
                ->action(function (array $data): void {
                    $filepath = Storage::disk('public')->path($data['file']);

                    if (!file_exists($filepath)) {
                        Notification::make()
                            ->danger()
                            ->title('File tidak ditemukan')
                            ->persistent()
                            ->send();
                        return;
                    }

                    try {
                        $rows = $this->parseFile($filepath, $data['file']);
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal membaca file')
                            ->body($e->getMessage())
                            ->send();
                        return;
                    }

                    $imported = 0;
                    $skipped  = 0;
                    $errors   = [];

                    foreach ($rows as $lineNumber => $row) {
                        $nama  = trim($row['nama'] ?? '');
                        $email = trim($row['email'] ?? '');

                        // Lewati baris kosong
                        if ($nama === '' && $email === '') {
                            continue;
                        }

                        if ($nama === '') {
                            $errors[] = "Baris {$lineNumber}: Nama tidak boleh kosong.";
                            $skipped++;
                            continue;
                        }

                        // Tentukan email
                        if ($email !== '') {
                            if (User::where('email', $email)->exists()) {
                                $errors[] = "Baris {$lineNumber}: Email '{$email}' sudah terdaftar.";
                                $skipped++;
                                continue;
                            }
                        } elseif ($data['auto_generate_email']) {
                            $emailName = Str::slug(str_replace([',', '.'], '', $nama), '.');
                            $email     = $emailName . '@' . $data['email_domain'];
                            $counter   = 1;
                            $original  = $email;
                            while (User::where('email', $email)->exists()) {
                                $email = str_replace('@', $counter . '@', $original);
                                $counter++;
                            }
                        } else {
                            $errors[] = "Baris {$lineNumber}: Email kosong dan generate otomatis tidak aktif.";
                            $skipped++;
                            continue;
                        }

                        // Validasi NIP duplikat
                        $nip = !empty($row['nip']) ? trim($row['nip']) : null;
                        if ($nip && Guru::where('nip', $nip)->exists()) {
                            $errors[] = "Baris {$lineNumber}: NIP '{$nip}' sudah terdaftar.";
                            $skipped++;
                            continue;
                        }

                        try {
                            $user = User::create([
                                'name'     => $nama,
                                'email'    => $email,
                                'password' => Hash::make($data['default_password']),
                            ]);

                            $user->assignRole('guru');

                            Guru::create([
                                'user_id'        => $user->id,
                                'nip'            => $nip,
                                'gelar_depan'    => !empty($row['gelar_depan']) ? trim($row['gelar_depan']) : null,
                                'gelar_belakang' => !empty($row['gelar_belakang']) ? trim($row['gelar_belakang']) : null,
                            ]);

                            $imported++;

                        } catch (\Throwable $e) {
                            $errors[] = "Baris {$lineNumber}: " . $e->getMessage();
                            $skipped++;
                        }
                    }

                    Storage::disk('public')->delete($data['file']);

                    if ($imported > 0) {
                        Notification::make()
                            ->success()
                            ->title("{$imported} guru berhasil diimport")
                            ->body($skipped > 0 ? "{$skipped} baris dilewati." : null)
                            ->send();
                    }

                    if (!empty($errors)) {
                        Notification::make()
                            ->warning()
                            ->title('Beberapa baris gagal')
                            ->body(implode("\n", array_slice($errors, 0, 5)))
                            ->persistent()
                            ->send();
                    }

                    if ($imported === 0 && empty($errors)) {
                        Notification::make()
                            ->warning()
                            ->title('Tidak ada data yang diimport')
                            ->body('Cek kembali format template.')
                            ->send();
                    }
                })
                ->modalHeading('Import Guru')
                ->modalDescription('Upload file Excel atau CSV sesuai template')
                ->modalSubmitActionLabel('Import Sekarang'),

            \Filament\Actions\CreateAction::make(),
        ];
    }

    private function parseFile(string $path, string $filename): array
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if ($ext === 'csv') {
            return $this->parseCsv($path);
        }

        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            return [];
        }

        $headers = array_map(fn($h) => strtolower(trim(str_replace('*', '', $h))), $rows[0]);
        $result  = [];

        foreach (array_slice($rows, 1) as $lineIdx => $row) {
            $assoc = [];
            foreach ($headers as $colIdx => $header) {
                $assoc[trim($header)] = isset($row[$colIdx]) ? trim($row[$colIdx]) : '';
            }
            $result[$lineIdx + 2] = $assoc;
        }

        return $result;
    }

    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');

        if (!$handle) {
            throw new \RuntimeException('Tidak bisa membuka file CSV.');
        }

        $headers = null;
        $result  = [];
        $line    = 1;

        while (($row = fgetcsv($handle)) !== false) {
            if ($headers === null) {
                $headers = array_map(fn($h) => strtolower(trim(str_replace('*', '', $h))), $row);
                $line++;
                continue;
            }

            $assoc = [];
            foreach ($headers as $idx => $key) {
                $assoc[$key] = isset($row[$idx]) ? trim($row[$idx]) : '';
            }

            $result[$line] = $assoc;
            $line++;
        }

        fclose($handle);

        return $result;
    }
}
