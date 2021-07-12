<?php


namespace App\Console\Commands;

use Illuminate\Console\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'base';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '基类';

    public function __construct()
    {
        parent::__construct();
        $this->resolveDependency();
    }

    public function resolveDependency()
    {
        require_once  __DIR__ . '/../../Customize/api/admin/plugin/extra/app.php';
    }
}
