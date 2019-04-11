<?php

namespace Agilap\Qud;

class UnknownJobCount extends JobCount
{
    public function isKnown() : bool
    {
        return false;
    }
}
