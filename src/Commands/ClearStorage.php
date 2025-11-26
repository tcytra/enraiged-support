<?php

namespace Enraiged\Commands;

use Enraiged\Filesystem\Filesystem;
use Illuminate\Console\Command;

class ClearStorage extends Command
{
    /** @var  string  The name and signature of the console command. */
    protected $signature = 'storage:clear';

    /** @var  string  The console command description. */
    protected $description = 'Removes all files from the storage/app subdirectories (defined in config/enraiged/storage.php).';

    /** @var  string  The storage root path prefix to prepend to each clearable directory. */
    private $storage_root = 'app/private';

    /**
     *  Create an instance of the storage:clear console command.
     *
     *  @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->storage_root = trim($this->storage_root, '/');
    }

    /**
     *  Execute the console command.
     *
     *  @return int
     */
    public function handle(): int
    {
        if (app()->environment('production') 
            && !$this->confirm('Application in production! Do you wish to continue?')) {
            return 1;
        }

        foreach (config('enraiged.storage.clear') as $each) {
            $storage_path = storage_path("{$this->storage_root}/{$each}");

            !is_dir($storage_path)
                || (new Filesystem)
                    ->clearDirectory($storage_path, 'gitignore');
        }

        $this->info('Successfully cleared storage directories.');

        return 0;
    }
}
