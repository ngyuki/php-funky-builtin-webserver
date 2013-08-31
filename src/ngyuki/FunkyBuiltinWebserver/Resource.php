<?php
namespace ngyuki\FunkyBuiltinWebserver;

class Resource
{
    public function directory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'resource';
    }

    public function filename($fn)
    {
        return $this->directory() . DIRECTORY_SEPARATOR . $fn;
    }
}
