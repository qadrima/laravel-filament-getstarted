<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Filament\Admin\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Permissions
    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view customers');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create customers');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('edit customers');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('delete customers');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Grid::make([
                    'default' => 1,
                    'md' => 2,      
                ])
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->label('Customer Name'),

                    TextInput::make('phone')
                        ->required()
                        ->label('Phone Number'),

                    Textarea::make('description')
                        ->columnSpanFull()
                        ->label('Description'),
                ]),

                Repeater::make('customerProducts')
                    ->relationship()
                    ->label('Products Purchased')
                    ->schema([

                        // Product Selection
                        Select::make('product_id')
                            ->label('Product')
                            ->options(function () {
                                return Product::all()->pluck('full_label', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = Product::find($state);
                                if ($product) {
                                    $set('product_name', $product->name);
                                    $set('product_price', $product->price);
                                }
                            }),
                        
                        // Product Name
                        Select::make('type')
                            ->label('Schedule Type')
                            ->options([
                                'specific' => 'Specific Dates',
                                'recurring' => 'Recurring Automatically',
                            ])
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === 'recurring') {
                                    $set('recurring_type', 'monthly');
                                } else {
                                    $set('recurring_type', null);
                                }

                                $set('specific_date', null);
                            }),

                        // Conditional fields based on the selected type
                        // Specific Dates
                        DatePicker::make('specific_date')
                            ->label('Pick a Date')
                            ->minDate(Carbon::today())
                            ->native(false)
                            ->required()
                            ->placeholder('Select a date')
                            ->visible(fn ($get) => $get('type') === 'specific'),

                        // Recurring type
                        Select::make('recurring_type')
                            ->label('Recurring Type')
                            ->options([
                                'weekly' => 'Weekly',
                                'monthly' => 'Monthly',
                            ])
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->visible(fn ($get) => $get('type') === 'recurring')
                            ->afterStateUpdated(function ($state, callable $set) {
                                
                                // Reset
                                $set('recurring', [
                                    'monday' => false,
                                    'tuesday' => false,
                                    'wednesday' => false,
                                    'thursday' => false,
                                    'friday' => false,
                                    'saturday' => false,
                                    'sunday' => false,
                                ]);
                                $set('recurring', collect(range(1, 28))->mapWithKeys(fn ($day) => ["$day" => false])->toArray());

                            }),
                        
                        // Recurring if weekly
                        Repeater::make('recurringWeekly')
                            ->relationship('recurringWeekly')
                            ->schema([
                                Select::make('day')
                                    ->label('Day of Week')
                                    ->options([
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ])
                                    ->required()
                                    ->rules([
                                        function ($attribute, $value, $fail) {

                                            $data = request()->input('data.recurringWeekly', []);
                                            $duplicates = collect($data)
                                                ->pluck('day')
                                                ->filter()
                                                ->duplicates();

                                            if ($duplicates->isNotEmpty()) {
                                                $fail('Invalid day of the week selected.');
                                            }
                                        }
                                    ]),
                            ])
                            ->hidden(fn ($get) => $get('recurring_type') !== 'weekly'),

                        // Recurring if monthly
                        Repeater::make('recurringMonthly')
                            ->relationship('recurringMonthly')
                            ->schema([
                                Select::make('day')
                                    ->label('Day of Month')
                                    ->options(array_combine(range(1, 28), range(1, 28)))
                                    ->required()
                                    ->rules([
                                        function ($attribute, $value, $fail) {

                                            $data = request()->input('data.recurringMonthly', []);
                                            $duplicates = collect($data)
                                                ->pluck('day')
                                                ->filter()
                                                ->duplicates();

                                            if ($duplicates->isNotEmpty()) {
                                                $fail('Invalid day of the month selected.');
                                            }
                                        }
                                    ]),
                            ])
                            ->hidden(fn ($get) => $get('recurring_type') !== 'monthly'),

                    ])
                    ->createItemButtonLabel('Add Product')
                    ->columnSpanFull()
                    ->collapsed(),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth('2xl'),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCustomers::route('/'),
            // 'create' => Pages\CreateCustomer::route('/create'),
            // 'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
