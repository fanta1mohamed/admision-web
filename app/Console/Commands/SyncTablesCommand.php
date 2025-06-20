<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncTablesCommand extends Command
{
    protected $signature = 'sync:tables {page=1}';
    protected $description = 'Synchronize data from source_table to destination_table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $page = $this->argument('page');
        $controller = new SyncController();
        $response = $controller->syncTables($page);

        if ($response->getData()->message === 'Batch synchronized successfully!') {
            $nextPage = $response->getData()->next_page;
            $this->call('sync:tables', ['page' => $nextPage]);
        } else {
            $this->info('Synchronization completed!');
        }
    }
}
