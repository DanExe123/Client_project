<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\ExpenseTable;
use App\Models\PaymentInvoice;
use App\Models\CashFlow;
use Carbon\CarbonPeriod;
use Carbon\Carbon;





class GenerateDailyCashFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-daily-cash-flow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minDate = collect([
            PaymentInvoice::min('created_at'),
            Payment::min('created_at'),
            ExpenseTable::min('created_at'),
        ])->filter()->min();

        $maxDate = now();

        if (!$minDate) {
            $this->info('No transactions found.');
            return;
        }

        $period = CarbonPeriod::create(
            Carbon::parse($minDate)->startOfDay(),
            Carbon::parse($maxDate)->endOfDay()
        );

        $runningBalance = 0;

        foreach ($period as $date) {
            $d = $date->format('Y-m-d');

            $customerPayments = PaymentInvoice::whereDate('created_at', $d)->sum('amount');
            $paymentToSupplier = Payment::whereDate('created_at', $d)->sum('amount_paid');
            $expenses = ExpenseTable::whereDate('created_at', $d)->sum('amount');

            $beginningBalance = $runningBalance;
            $endingBalance = $beginningBalance + $customerPayments - $paymentToSupplier - $expenses;

            CashFlow::updateOrCreate(
                ['date' => $d],
                [
                    'beginning_balance' => $beginningBalance,
                    'customer_payments' => $customerPayments,
                    'payment_to_supplier' => $paymentToSupplier,
                    'expenses' => $expenses,
                    'ending_balance' => $endingBalance,
                ]
            );

            $runningBalance = $endingBalance;
        }

        $this->info('Cash flow generated successfully.');
    }
}
