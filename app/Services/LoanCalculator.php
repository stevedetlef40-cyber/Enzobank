<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanPayment;
use App\Support\BusinessDay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanCalculator
{
    public function generateSchedule(Loan $loan): void
    {
        $start = $loan->start_date ? Carbon::parse($loan->start_date) : Carbon::today();
        $freq = $loan->payment_frequency ?? 'monthly';
        $n = (int) $loan->term_months;
        $balance = (float) $loan->principal;
        $r = ((float) $loan->interest_rate) / 100.0;
        $method = $loan->interest_method ?? 'amortized';

        DB::transaction(function () use ($loan, $start, $freq, $n, $balance, $r, $method) {
            $loan->payments()->delete();
            $periods = $this->periodsForFrequency($freq, $n);
            $perRate = $this->periodRate($r, $freq);
            $due = $this->firstDueDate($start, $freq);

            $paymentAmount = 0.0;
            if ($method === 'amortized') {
                $paymentAmount = $this->amortizedPayment($balance, $perRate, $periods);
            }

            $nextDue = null;
            for ($i = 1; $i <= $periods; $i++) {
                $adjDue = BusinessDay::nextBusinessDay($due->copy());
                $interest = 0.0;
                $principal = 0.0;
                $fee = 0.0;

                if ($method === 'simple') {
                    $days = max(1, $due->diffInDays($due->copy()->subMonthsNoOverflow(1)));
                    $interest = $balance * $r * ($days / 365);
                    $principal = $this->simplePrincipalPortion($balance, $periods - $i + 1);
                } elseif ($method === 'compound') {
                    $interest = $balance * $perRate;
                    $principal = $this->compoundPrincipalPortion($balance, $perRate, $periods - $i + 1);
                } else {
                    $interest = $balance * $perRate;
                    $principal = max(0.0, $paymentAmount - $interest);
                }

                $total = $principal + $interest + $fee;
                $balance = max(0.0, $balance - $principal);

                LoanPayment::create([
                    'loan_id' => $loan->id,
                    'period_number' => $i,
                    'due_date' => $adjDue->toDateString(),
                    'amount_due' => $total,
                    'principal_due' => $principal,
                    'interest_due' => $interest,
                    'fee_due' => $fee,
                    'amount_paid' => 0,
                    'principal_paid' => 0,
                    'interest_paid' => 0,
                    'fee_paid' => 0,
                    'remaining_principal' => $balance,
                    'status' => 'due',
                ]);

                if ($nextDue === null) {
                    $nextDue = $adjDue->copy();
                }

                $due = $this->incrementDueDate($due, $freq);
            }

            $loan->balance_principal = $loan->principal;
            $loan->next_due_date = $nextDue;
            $loan->accrued_interest = 0;
            $loan->last_accrual_date = $loan->start_date ?: Carbon::today()->toDateString();
            $loan->save();
        });
    }

    public function accrueInterestTo(Loan $loan, Carbon $toDate): void
    {
        $last = $loan->last_accrual_date ? Carbon::parse($loan->last_accrual_date) : ($loan->start_date ? Carbon::parse($loan->start_date) : Carbon::today());
        if ($toDate->lessThanOrEqualTo($last)) {
            return;
        }
        $days = $last->diffInDays($toDate);
        $rate = ((float) $loan->interest_rate) / 100.0;
        $daily = $rate / 365;
        $accrued = $loan->accrued_interest + ($loan->balance_principal * $daily * $days);
        $loan->accrued_interest = $accrued;
        $loan->last_accrual_date = $toDate->toDateString();
        $loan->save();
    }

    public function earlySettlementAmount(Loan $loan): float
    {
        $fee = ((float) $loan->early_settlement_fee_percent) / 100.0;
        return max(0.0, (float) $loan->balance_principal + (float) $loan->accrued_interest) * (1 + $fee);
    }

    protected function periodsForFrequency(string $freq, int $months): int
    {
        if ($freq === 'biweekly') {
            return (int) ceil(($months * 30) / 14);
        }
        if ($freq === 'weekly') {
            return (int) ceil(($months * 30) / 7);
        }
        return $months;
    }

    protected function periodRate(float $annualRate, string $freq): float
    {
        if ($freq === 'biweekly') {
            return $annualRate / 26;
        }
        if ($freq === 'weekly') {
            return $annualRate / 52;
        }
        return $annualRate / 12;
    }
    public function computePeriodRate(float $annualRate, string $freq): float
    {
        return $this->periodRate($annualRate, $freq);
    }

    public function applyLateFees(Loan $loan, Carbon $asOf): void
    {
        $grace = (int) ($loan->grace_days ?? 0);
        $type = $loan->late_fee_type ?? 'percent';
        $val = (float) ($loan->late_fee_value ?? 0);
        $payments = $loan->payments()
            ->where('status', 'due')
            ->whereDate('due_date', '<', $asOf->copy()->subDays($grace)->toDateString())
            ->get();
        foreach ($payments as $p) {
            $base = (float) $p->principal_due + (float) $p->interest_due;
            $fee = $type === 'percent' ? $base * ($val / 100.0) : $val;
            $p->fee_due = max((float) $p->fee_due, $fee);
            $p->amount_due = (float) $p->principal_due + (float) $p->interest_due + (float) $p->fee_due;
            $p->status = 'late';
            $p->save();
        }
    }

    protected function amortizedPayment(float $principal, float $perRate, int $periods): float
    {
        if ($perRate <= 0) {
            return $periods > 0 ? $principal / $periods : 0;
        }
        $f = pow(1 + $perRate, $periods);
        return ($principal * $perRate * $f) / ($f - 1);
    }
    public function computeAmortizedPayment(float $principal, float $annualRate, string $freq, int $periods): float
    {
        $perRate = $this->periodRate($annualRate, $freq);
        return $this->amortizedPayment($principal, $perRate, $periods);
    }

    protected function simplePrincipalPortion(float $balance, int $remaining): float
    {
        if ($remaining <= 0) {
            return $balance;
        }
        return $balance / $remaining;
    }

    protected function compoundPrincipalPortion(float $balance, float $perRate, int $remaining): float
    {
        if ($perRate <= 0) {
            return $this->simplePrincipalPortion($balance, $remaining);
        }
        $pay = $this->amortizedPayment($balance, $perRate, $remaining);
        $interest = $balance * $perRate;
        $principal = max(0.0, $pay - $interest);
        return $principal;
    }

    protected function firstDueDate(Carbon $start, string $freq): Carbon
    {
        if ($freq === 'biweekly') {
            return $start->copy()->addDays(14);
        }
        if ($freq === 'weekly') {
            return $start->copy()->addDays(7);
        }
        return $start->copy()->addMonthNoOverflow();
    }

    protected function incrementDueDate(Carbon $date, string $freq): Carbon
    {
        if ($freq === 'biweekly') {
            return $date->copy()->addDays(14);
        }
        if ($freq === 'weekly') {
            return $date->copy()->addDays(7);
        }
        return $date->copy()->addMonthNoOverflow();
    }
}
