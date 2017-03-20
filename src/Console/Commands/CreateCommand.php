<?php

namespace Beeldvoerders\Reset\Console\Commands;

use Beeldvoerders\Reset\Services\DatabaseService;
use Beeldvoerders\Reset\Services\ZipService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateCommand extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the backup on which the reset will be based';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->info('Starting to create the backup file');

        // Zip current directory
        $file = ZipService::create('temporary.zip');

        if(!empty(config('reset.directories')))
        {
            $file->addDirectories(config('reset.directories'));
        }

        if(!empty(config('reset.database')))
        {
            $file->addFile(DatabaseService::export());

            unlink(storage_path('mysqldump.sql'));
        }

        $file = $file->getFile();

		// Save to disc
		Storage::disk(config('reset.disk'))
			->put(
				'reset.zip',
				file_get_contents($file)
			);

		// Remove old file
		unlink($file);

		$this->info('Backup file created and stored to disc');
    }
}