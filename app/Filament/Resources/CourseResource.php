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
    protected static ?string $navigationLabel = 'Mata Pelajaran Saya'; // ✅ LABEL LEBIH RAMAH
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $pluralLabel = 'Mata Pelajaran';
    protected static ?string $modelLabel = 'Mata Pelajaran';
    protected static ?int $navigationSort = 30; // ✅ DIUBAH

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Mata Pelajaran')
                    ->description('Mata pelajaran untuk kelas tertentu')
                    ->schema([
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

                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(function (Get $get) {
                                $guruId = null;
                                if (auth()->user()->hasRole('admin')) {
                                    $guruId = $get('guru_id');
                                } else {
                                    $guruId = auth()->user()->guru?->id;
                                }
                                if (!$guruId) {
                                    return [];
                                }
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

                        Forms\Components\Select::make('mapel_id')
                            ->label('Mata Pelajaran')
                            ->options(function (Get $get) {
                                $guruId = null;
                                $kelasId = $get('kelas_id');
                                if (auth()->user()->hasRole('admin')) {
                                    $guruId = $get('guru_id');
                                } else {
                                    $guruId = auth()->user()->guru?->id;
                                }
                                if (!$guruId || !$kelasId) {
                                    return [];
                                }
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
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Course')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        default => 'gray',
                    }),
            ])
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
            ->defaultSort('created_at', 'desc');
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->hasRole('guru')) {
            $guruId = Auth::user()->guru?->id;
            if ($guruId) {
                $query->where('guru_id', $guruId);
            }
        }

        $query->with(['kelas', 'mapel'])
              ->withCount('moduls');

        return $query;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'guru']);
    }
}