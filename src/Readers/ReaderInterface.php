<?php namespace Maer\Config\Readers;

interface ReaderInterface
{
    /**
     * Parse a file and return an array
     *
     * @param  string $file
     *
     * @return array
     */
    public function read($file);
}
