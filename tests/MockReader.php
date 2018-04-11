<?php

use Maer\Config\Readers\ReaderInterface;

class MockReader implements ReaderInterface
{
    public function read($file)
    {
        $content = file_get_contents($file);
        return ['hello' => ['world' => $content]];
    }
}
