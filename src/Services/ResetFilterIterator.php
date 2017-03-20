<?php

namespace Beeldvoerders\Reset\Services;

use RecursiveFilterIterator;

class ResetFilterIterator extends RecursiveFilterIterator
{
	public function accept()
    {
        $excludes = array('reset','packages');

        return !($this->isDir() && in_array($this->getFilename(), $excludes));
    }
}