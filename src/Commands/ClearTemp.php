<?php

namespace Enraiged\Commands;

use Enraiged\Filesystem\Filesystem;
use Illuminate\Console\Command;

class ClearTemp extends Command
{
    /** @var  string  The name and signature of the console command. */
    protected $signature = 'temp:clear';

    /** @var  string  The console command description. */
    protected $description = 'Removes all files from the storage/app/temp directory.';

    /**
     *  Execute the console command.
     *
     *  @return int
     */
    public function handle(): int
    {
        $temp_path = storage_path('app/temp');

        (new Filesystem)->clearDirectory($temp_path, 'gitignore');

        $this->info('Successfully cleared temp storage directory.');

        return 0;
    }
}
