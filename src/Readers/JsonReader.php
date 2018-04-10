<?php namespace Maer\Config\Readers;

class JsonReader implements ReaderInterface
{
    public function read($file)
    {
        $content = @json_decode(file_get_contents($file), true, 512);
        return is_array($content) ? $content : [];
    }
}
