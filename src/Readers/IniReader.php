<?php namespace Maer\Config\Readers;

class IniReader implements ReaderInterface
{
    public function read($file)
    {
        $content = parse_ini_file($file, true);
        return is_array($content) ? $content : [];
    }
}
