<?php

namespace Tests\Unit;

use App\Services\LoanCalculator;
use App\Support\BusinessDay;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class LoanCalculatorTest extends TestCase
{
    public function test_period_rate_by_frequency()
    {
        $svc = new LoanCalculator();
        $this->assertEqualsWithDelta(0.05/12, $svc->computePeriodRate(0.05, 'monthly'), 1e-9);
        $this->assertEqualsWithDelta(0.12/26, $svc->computePeriodRate(0.12, 'biweekly'), 1e-9);
        $this->assertEqualsWithDelta(0.10/52, $svc->computePeriodRate(0.10, 'weekly'), 1e-9);
    }

    public function test_amortized_payment_basic()
    {
        $svc = new LoanCalculator();
        $p = $svc->computeAmortizedPayment(10000, 0.12, 'monthly', 12);
        $this->assertTrue($p > 0);
    }

    public function test_business_day_weekend_adjustment()
    {
        $sat = Carbon::parse('2026-03-14');
        $mon = BusinessDay::nextBusinessDay($sat);
        $this->assertEquals('2026-03-16', $mon->toDateString());
    }
}

