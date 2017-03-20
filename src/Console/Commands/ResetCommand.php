<?php

namespace Beeldvoerders\Reset\Console\Commands;

use Beeldvoerders\Reset\Services\DatabaseService;
use Beeldvoerders\Reset\Services\FileService;
use Beeldvoerders\Reset\Services\ResetFilterIterator;
use Beeldvoerders\Reset\Services\ZipService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ResetCommand extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the current file directory and database to the backed-up one';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->info('Starting the reset process');

        // Check if backup file exists
        if( ! Storage::disk(config('reset.disk'))->exists('reset.zip'))
        {
            $this->error('Backup file does not exists. Please create it first using php artisan reset:backup');

            return;
        }

        // Remove the old content, transfer the remote
        // back-up file and unzip the contents to the
        // root of the application.
        foreach(config('reset.directories') AS $directory)
        {
            File::deleteDirectory($directory);
        }

        if(config('reset.disk') != 'local')
        {
            file_put_contents(
                storage_path('reset.zip'),
                Storage::disk(config('reset.disk'))->get('reset.zip')
            );
        }
        
        ZipService::unzip('reset.zip', base_path());

        // Empty the database and import the dump file that is unzipped in
        // the root directory.
        if(!empty(config('reset.database')))
        {
            DatabaseService::empty();
            DatabaseService::import('mysqldump.sql');
        }
        
        unlink(storage_path('mysqldump.sql'));
        
		$this->info('Application restored to backed-up state');
    }
}