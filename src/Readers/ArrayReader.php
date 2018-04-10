<?php namespace Maer\Config\Readers;

class ArrayReader implements ReaderInterface
{
    public function read($file)
    {
        $content = include $file;
        return is_array($content) ? $content : [];
    }
}
