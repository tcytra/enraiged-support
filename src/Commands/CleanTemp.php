<?php

namespace Enraiged\Commands;

use Enraiged\Filesystem\Filesystem;
use Illuminate\Console\Command;

class CleanTemp extends Command
{
    /** @var  string  The name and signature of the console command. */
    protected $signature = 'clean:temp';

    /** @var  string  The console command description. */
    protected $description = 'Removes all files from the storage/app/temp directory.';

    /**
     *  Execute the console command.
     *
     *  @return int
     */
    public function handle(): int
    {
        $temp_path = storage_path('app/private/temp');

        (new Filesystem)->cleanDirectory($temp_path, 'gitignore');

        $this->info('Successfully cleared temp storage directory.');

        return 0;
    }
}
