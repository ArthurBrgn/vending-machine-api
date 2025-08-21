<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\BalanceRechargeLog;
use App\Models\Classification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class RechargeEmployeeDailyBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recharge-employee-daily-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recharge employee daily balance by their classification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return DB::transaction(function () {
            $classifications = Classification::query()
                ->with('employees')
                ->get();

            $balanceRechargeLogData = [];
            $now = now();

            foreach ($classifications as $classification) {
                foreach ($classification->employees as $employee) {
                    $oldBalance = $employee->current_points;

                    $employee->current_points = $classification->daily_points_limit;
                    $employee->save();

                    $balanceRechargeLogData[] = [
                        'employee_id' => $employee->id,
                        'old_balance' => $oldBalance,
                        'new_balance' => $classification->daily_points_limit,
                        'recharge_date' => $now,
                    ];
                }
            }

            BalanceRechargeLog::insert($balanceRechargeLogData);

            $this->info('Employee daily balance recharged successfully.');

            return self::SUCCESS;
        }, 3);
    }
}
