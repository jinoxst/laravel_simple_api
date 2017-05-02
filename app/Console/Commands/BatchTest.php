<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Reservation;


class BatchTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'batch sampling';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $json = Reservation::where('id', 5)->first()->toJson();
        $this->info($json);
    }
}
