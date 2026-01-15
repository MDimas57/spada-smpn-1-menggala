<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\ModulsRelationManager;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Kelola Course';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $pluralLabel = 'Course';
    protected static ?string $modelLabel = 'Course';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Course')
                    ->description('Course adalah mata pelajaran untuk kelas tertentu')
                    ->schema([
                        // Input Guru (Khusus Admin)
                        Forms\Components\Select::make('guru_id')
                            ->label('Guru Pengampu')
                            ->options(\App\Models\Guru::with('user')->get()->pluck('user.name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(fn () => auth()->user()->hasRole('admin'))
                            ->visible(fn () => auth()->user()->hasRole('admin'))
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('kelas_id', null);
                                $set('mapel_id', null);
                            }),

                        // Pilih Kelas (dinamis berdasarkan guru)
                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(function (Get $get) {
                                $guruId = null;

                                // Jika user adalah admin, gunakan guru_id dari form
                                if (auth()->user()->hasRole('admin')) {
                                    $guruId = $get('guru_id');
                                } else {
                                    // Jika user adalah guru, gunakan guru_id dari user yang login
                                    $guruId = auth()->user()->guru?->id;
                                }

                                if (!$guruId) {
                                    return [];
                                }

                                // Query kelas yang diajar oleh guru
                                return \App\Models\Kelas::whereHas('gurus', function (Builder $q) use ($guruId) {
                                    $q->where('gurus.id', $guruId);
                                })
                                ->pluck('nama', 'id')
                                ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('mapel_id', null);
                            }),

                        // Pilih Mapel (filtered by Guru dan Kelas)
                        Forms\Components\Select::make('mapel_id')
                            ->label('Mata Pelajaran')
                            ->options(function (Get $get) {
                                $guruId = null;
                                $kelasId = $get('kelas_id');

                                // Jika user adalah admin, gunakan guru_id dari form
                                if (auth()->user()->hasRole('admin')) {
                                    $guruId = $get('guru_id');
                                } else {
                                    // Jika user adalah guru, gunakan guru_id dari user yang login
                                    $guruId = auth()->user()->guru?->id;
                                }

                                if (!$guruId || !$kelasId) {
                                    return [];
                                }

                                // Query mapel yang diajar oleh guru DAN ada jadwal di kelas tersebut
                                return \App\Models\Mapel::whereHas('gurus', function (Builder $q) use ($guruId) {
                                    $q->where('gurus.id', $guruId);
                                })
                                ->whereHas('jadwalPelajaran', function (Builder $q) use ($kelasId, $guruId) {
                                    $q->where('kelas_id', $kelasId)
                                      ->where('guru_id', $guruId);
                                })
                                ->orderBy('nama')
                                ->pluck('nama', 'id')
                                ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder(
                                fn(Get $get) =>
                                !$get('kelas_id') ? 'Pilih Kelas Terlebih Dahulu' : 'Pilih Mata Pelajaran'
                            ),

                        // Nama Course (otomatis terisi)
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Course')
                            ->placeholder('Contoh: Matematika - X IPA 1')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('deskripsi')
                            ->label('Deskripsi Course')
                            ->placeholder('Jelaskan tujuan dan gambaran umum course ini...')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship('kelas', 'nama')
                    ->label('Filter Kelas'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->content(view('filament.resources.course-resource.cards'))
            ->contentGrid([
                'default' => 1,
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ModulsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    // Filter: Guru hanya melihat course miliknya
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->hasRole('guru')) {
            $guruId = Auth::user()->guru?->id;
            if ($guruId) {
                $query->where('guru_id', $guruId);
            }
        }

        // Eager-load relations used in the cards view and include modul counts
        $query->with(['kelas', 'mapel'])
              ->withCount('moduls');

        return $query;
    }

    // Hanya Admin & Guru yang bisa akses
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'guru']);
    }
}
