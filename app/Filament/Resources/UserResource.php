<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                ->maxLength(255)
                ->required(),

                Forms\Components\TextInput::make('email')
                ->maxLength(255)
                ->email()
                ->required(),

                Forms\Components\TextInput::make('password')
                ->helperText('Minimum 9 Karakter')
                ->password()
                ->required()
                ->minLength(9)
                ->maxLength(255),

                Forms\Components\Select::make('occupation')
                ->options([
                'Developer' => 'Developer',
                'Designer' => 'Designer',
                'Projek Manajer' => 'Projek Manajer',
                ])
                ->required(),

                Forms\Components\Select::make('roles')
                ->label('role')
                ->relationship('roles', 'name')
                ->required(),

                Forms\Components\FileUpload::make('photo')
                ->required()
                ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\ImageColumn::make('photo'), // Pastikan jalur file benar
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('roles.name'),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
