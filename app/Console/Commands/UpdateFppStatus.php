<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateFppStatus extends Command
{
    protected $signature = 'update:fpp-status';
    protected $description = 'Update the fpp status based on expire_date';

    public function handle(): void
    {
        $currentDate = now()->toDateString();

        DB::table('users_networks')->where('expire_date', '<=', $currentDate)
            ->update(['fpp' => false]);

        $this->info('Fpp status updated successfully.');
    }
}
