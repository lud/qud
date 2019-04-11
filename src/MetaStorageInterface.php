<?php

namespace Agilap\Qud;

interface MetaStorageInterface
{
    public function setMeta(string $key, $value) : void;
    public function getMeta(string $key);
}
