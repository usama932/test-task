<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class test-task extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('products')->where('created_at', '<=', Carbon:: now()->subDays(30))->delete();
    }
}
