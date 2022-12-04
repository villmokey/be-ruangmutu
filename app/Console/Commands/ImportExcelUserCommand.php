<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Excel;
use App\Imports\UsersImport;

class ImportExcelUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import user from excel';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Generating');
        Excel::import(new UsersImport, public_path('/InitialUserList.xlsx'));
        $this->info('Done');
        exit();
    }
}
