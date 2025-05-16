<?php

namespace App\Filament\Admin\Resources\CustomerResource\Pages;

use App\Filament\Admin\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('2xl'),

                # actions jika menggunakan modal
                // ->using(function (array $data) {
                //     // === ✅ Tahap 1: Seperti mutateFormDataBeforeCreate ===
                //     $data['name'] = strtoupper($data['name']);

                //     return DB::transaction(function () use ($data) {

                //         // === ✅ Tahap 2: Seperti handleRecordCreation ===
                //         $customer = Customer::create($data);

                //         // === ✅ Tahap 3: Seperti afterCreate ===
                //         \Log::info('Customer created via modal:', [
                //             'data' => $data,
                //         ]);

                //         return $customer;
                //     });
                // }),

        ];
    }
}
