<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmiSchedule;

class CheckOverdueEmis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emi:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue EMIs and apply penalties';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all pending EMIs with due date in the past
        $overdueEmis = EmiSchedule::where('status', 'Pending')
            ->where('due_date', '<', now())
            ->get();

        $count = 0;
        foreach ($overdueEmis as $emi) {
            $emi->markAsOverdue();
            $count++;
        }

        $this->info("✓ Checked {$count} EMIs. {$count} marked as overdue.");
        return Command::SUCCESS;
    }
}
