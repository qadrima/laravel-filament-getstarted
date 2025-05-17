<?php

namespace App\Actions;

use App\Models\RecurringMonthly;
use App\Models\CustomerProductRecurringMonthly;

class ProcessRecurringMonthlySchedule
{
    public function handle(string $today, bool $isStore = false): array
    {
        dump($today);

        $data = CustomerProductRecurringMonthly::select('id', 'day', 'customer_product_id')
                    ->with(['customerProduct' => function ($q) {
                        $q->select('id', 'customer_id', 'product_id')
                        ->with([
                            'product:id,name,price'
                        ]);
                    }])
                    ->where('day', '=', $today)
                    ->get()
                    ->toArray();

        foreach ($data as $key => $value) 
        {
            $res = [
                'customer_id' => $value['customer_product']['customer_id'],
                'product_id' => $value['customer_product']['product_id'],
                'price' => $value['customer_product']['product']['price'],
                
            ];
            dump($res);
        }

        return [
            'results' => count($data)
        ];
    }
}
