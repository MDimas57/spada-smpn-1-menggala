<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModulResource\Pages;
use App\Filament\Resources\ModulResource\RelationManagers;
use App\Models\Modul;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ModulResource extends Resource
{
    protected static ?string $model = Modul::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Akademik';
     protected static ?string $navigationLabel = 'Modul';
    protected static ?string $pluralLabel = 'Modul';
    protected static ?string $modelLabel = 'Modul';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Modul')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul Materi')
                            ->columnSpanFull(),
                            

                        // PILIHAN 1: Pilih Course (Recommended)
                        Forms\Components\Select::make('course_id')
                            ->label('Pilih Course')
                            ->relationship(
                                name: 'course',
                                titleAttribute: 'nama',
                                modifyQueryUsing: function (Builder $query) {
                                    if (auth()->user()->hasRole('guru')) {
                                        $guruId = auth()->user()->guru?->id;
                                        return $query->where('guru_id', $guruId);
                                    }
                                    return $query;
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $course = \App\Models\Course::find($state);
                                    if ($course) {
                                        $set('guru_id', $course->guru_id);
                                        $set('kelas_id', $course->kelas_id);
                                        $set('mapel_id', $course->mapel_id);
                                    }
                                }
                            })
                            ->helperText('Pilih course untuk auto-fill guru, kelas, dan mapel'),

                        // ATAU: Input Manual (jika course belum dipilih)
                        Forms\Components\Select::make('guru_id')
                            ->label('Guru Pengampu')
                            ->options(\App\Models\Guru::with('user')->get()->pluck('user.name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(fn () => auth()->user()->hasRole('admin'))
                            ->visible(fn () => auth()->user()->hasRole('admin'))
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('kelas_id', null)),

                        Forms\Components\RichEditor::make('deskripsi')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('kelas_id')
                            ->relationship(
                                name: 'kelas',
                                titleAttribute: 'nama',
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                    if (auth()->user()->hasRole('guru')) {
                                        $guruId = auth()->user()->guru?->id;
                                        return $query->whereHas('gurus', function ($q) use ($guruId) {
                                            $q->where('gurus.id', $guruId);
                                        });
                                    }

                                    $selectedGuruId = $get('guru_id');
                                    if ($selectedGuruId) {
                                        return $query->whereHas('gurus', function ($q) use ($selectedGuruId) {
                                            $q->where('gurus.id', $selectedGuruId);
                                        });
                                    }

                                    return $query;
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Kelas'),

                        Forms\Components\Select::make('mapel_id')
                            ->relationship('mapel', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\DateTimePicker::make('publish_at')
                            ->label('Jadwal Publish'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.nama')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('kelas.nama')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('mapel.nama')
                    ->sortable(),

                Tables\Columns\TextColumn::make('guru.user.name')
                    ->label('Guru')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MaterisRelationManager::class,
            RelationManagers\TugasRelationManager::class,
            RelationManagers\KuisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModuls::route('/'),
            'create' => Pages\CreateModul::route('/create'),
            'edit' => Pages\EditModul::route('/{record}/edit'),
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

        return $query;
    }
}
