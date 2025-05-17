<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Actions\ProcessRecurringMonthlySchedule;

class ProcessRecurringMonthlyScheduleTest extends TestCase
{
    public function test_generate_monthly_schedule()
    {   
        // $today = date('d');
        $today = '17';

        $action = new ProcessRecurringMonthlySchedule();
        $result = $action->handle($today);
        
        $this->assertEquals(
            [
                'results' => 2
            ],
            $result
        );
    }
}
