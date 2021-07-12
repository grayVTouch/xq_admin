<?php

namespace App\Console\Commands;

use Exception;

class Initialization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '系统初始化';

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
        $artisan = base_path('artisan');
        // 数据填充
        exec(sprintf('php %s migrate:refresh --seed' , $artisan));
    }
}
