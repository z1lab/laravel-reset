<?php

namespace Beeldvoerders\Reset\Services;

class FileService
{
	/**
	 * Move all files in $directory to $destination
	 *
	 * @param  string $directory
	 * @param  string $destination
	 * @return void
	 */
	public static function moveDirectory( $directory, $destination )
	{
        @mkdir($destination);

        // Create directories and move the files
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new ResetFilterIterator(
                    new \RecursiveDirectoryIterator(
                        $directory,
                        \RecursiveDirectoryIterator::SKIP_DOTS
                    )
                ),
                \RecursiveIteratorIterator::SELF_FIRST) 
            as $item
            )
        {
            if ($item->isDir())
                mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            else
                rename($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        }

        // Remove all directories that remain
        foreach(
        	$iterator = new ResetFilterIterator(
	                new \RecursiveDirectoryIterator(
	                    $directory,
	                    \RecursiveDirectoryIterator::SKIP_DOTS
	                )
	            )
        	as $item
        )
        {
        	if($item->isDir())
        		rmdir($item);
        }
	}
}