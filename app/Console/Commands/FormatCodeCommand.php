<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FormatCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'format:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Format code using Pint and analyze with PHPStan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running Pint...');
        exec('php -d memory_limit=1G ./vendor/bin/pint');

        $this->info('Running PHPStan...');
        exec('./vendor/bin/phpstan analyse --memory-limit=1G');

        $this->info('Code formatting completed!');
    }
}
