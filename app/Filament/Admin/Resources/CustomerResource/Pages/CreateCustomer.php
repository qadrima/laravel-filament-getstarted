<?php

namespace App\Filament\Admin\Resources\CustomerResource\Pages;

use App\Filament\Admin\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\CustomerProductRecurringWeekly;
use App\Models\CustomerProductRecurringMonthly;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    # override jika tidak menggunakan modal (modalWidth)
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     \Log::info('Creating customer with data:');
    //     return $data;
    // }

    // protected function handleRecordCreation(array $data): Model
    // {
    //     return DB::transaction(function () use ($data) {
    //         return static::getModel()::create($data);
    //     });
    // }

    // protected function afterCreate(): void
    // {
    //     \Log::debug('Customer berhasil dibuat:', ['customer' => $this->record]);
    // }
}
