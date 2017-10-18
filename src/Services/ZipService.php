<?php

namespace Beeldvoerders\Reset\Services;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Spatie\DbDumper\Databases\MySql;
use ZipArchive;

class ZipService
{
	/**
	 * The local location of the temporary zipfile
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * Create a new zip file
	 *
	 * @param  strng $file
	 * @return Beeldvoerders\Reset\Services\ZipService
	 */
	public static function create( $file )
	{
		$archive = new self();
		$archive->file = $file;

		@unlink($archive->file);

        return $archive;
	}

	/**
	 * Add the contents of multiple directories to the zip file
	 *
	 * @param  array $directories
	 * @return void
	 */
	public function addDirectories( array $directories = [] )
	{
		$zip = new ZipArchive();
        	$zip->open($this->file, ZipArchive::CREATE);

		foreach($directories AS $directory)
		{
			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($directory),
				RecursiveIteratorIterator::LEAVES_ONLY
			);

			foreach ($files as $name => $file)
			{
			    if (!$file->isDir())
			    {
			        $filepointer = $file->getPathName();

			        $zip->addFile(
			        	$filepointer, 
			        	substr($filepointer, strlen(base_path()) + 1)
			        );
			    }
			}
		}

		$zip->close();
	}

	/**
	 * Add a single file
	 *
	 * @param string $filepointer
	 */
	public function addFile( $filepointer )
	{
		$zip = new ZipArchive();
        $zip->open($this->file, ZipArchive::CREATE);

        $zip->addFile(
        	$filepointer,
        	substr($filepointer, strlen(base_path()) + 1)
        );

        $zip->close();
	}

	/**
	 * Get the local filepointer
	 *
	 * @return string
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * Unzip $file to $directory
	 *
	 * @param  string $file
	 * @param  string $directory
	 * @return void
	 */
	public static function unzip( $file, $directory )
	{
		$zip = new ZipArchive;

		if( ! $zip->open($file))
			throw new \Exception('Zip file not found');

		$zip->extractTo($directory);

		$zip->close();
	}
}
