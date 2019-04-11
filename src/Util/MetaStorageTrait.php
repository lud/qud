<?php

namespace Agilap\Qud\Util;
use Agilap\Qud\MetaStorageInterface;

trait MetaStorageTrait
{
    private $MetaStorageTrait__data;

    final public function meta($key = null, $value = null) {
        // we keep $this->MetaStorageTrait__data null as long as we
        // can for faster serialization
        if ($key === null) {
            return (array) $this->MetaStorageTrait__data;
        }
        $meta = (array) $this->MetaStorageTrait__data;
        if ($value === null) {
            return $meta[$key] ?? null;
        }
        $meta[$key] = $value;
        $this->MetaStorageTrait__data = $meta;
    }

    public function setMeta(string $key, $value) : void
    {
        $this->meta($key, $value);
    }

    public function getMeta(string $key)
    {
        return $this->meta($key);
    }

}
