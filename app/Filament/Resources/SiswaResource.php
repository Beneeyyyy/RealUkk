<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;

use Filament\Tables\Columns\ImageColumn;

use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Data Siswa';
    protected static ?string $pluralModelLabel = 'Data Siswa';
    protected static ?string $modelLabel = 'Siswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Siswa')
                            ->required(),

                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3)
                            ->required(),

                        Forms\Components\TextInput::make('kontak')
                            ->label('Kontak')
                            ->tel()
                            ->prefix('+62')
                            ->hint('Masukkan nomor tanpa awalan 0. Contoh: 81234567890')
                            ->required()
                            ->rules(['regex:/^[1-9][0-9]{7,14}$/'])
                            ->afterStateHydrated(function (TextInput $component, $state) {
                                $component->state(str_replace('+62', '', $state));
                            })
                            ->dehydrateStateUsing(function ($state) {
                                return '+62' . ltrim($state, '0');
                            }),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->minLength(8),

                        Forms\Components\FileUpload::make('gambar')
                            ->label('Foto Siswa')
                            ->disk('public')
                            ->directory('siswa')
                            ->image()
                            ->required(),

                        Forms\Components\Select::make('status_pkl')
                            ->label('Status PKL')
                            ->options([
                                false => 'Belum PKL',
                                true => 'Sedang PKL',
                            ])
                            ->default(false)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Foto Siswa')
                    ->disk('public')
                    ->circular(),




                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin'),

                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat'),

                Tables\Columns\BooleanColumn::make('status_pkl')
                    ->label('Status PKL'),


            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
