<?php

namespace Agilap\Qud;

class FixedJobCount extends JobCount
{
    private $n;

    public function __construct(int $n)
    {
        $this->n = $n;
    }

    public function isKnown() : bool
    {
        return true;
    }

    public function getValue() : int
    {
        return $this->n;
    }
}
