<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;

use App\Models\Course;
use App\Models\CourseSection;
use App\Filament\Resources\CourseResource\RelationManagers\CourseSectionRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\CourseSectionsRelationManager;
use Doctrine\DBAL\Query\From;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->required()
                            ->image(),
                    ]),

                Fieldset::make('Additional')
                    ->schema([
                        Forms\Components\Repeater::make('benefits')
                            ->relationship('benefits')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ]),

                        Forms\Components\Textarea::make('about')
                            ->label('Description')
                            ->required(),

                        Forms\Components\Select::make('is_popular')
                            ->options([
                                //
                                true => 'Popular',
                                false => 'Not Popular',
                            ])
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                Tables\Columns\ImageColumn::make('thumbnail'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name'),

                Tables\Columns\IconColumn::make('is_popular')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueicon('heroicon-o-check-circle')
                    ->falseicon('heroicon-o-x-circle')
                    ->label('Popular'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            CourseSectionsRelationManager::class,
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
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
