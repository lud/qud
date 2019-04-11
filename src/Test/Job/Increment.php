<?php

namespace Agilap\Qud\Test\Job;
use Agilap\Qud\{JobHandlerInterface, JobInterface, AbstractJob};

class Increment extends AbstractJob
{
    private $n;

    public function __construct(int $n)
    {
        $this->n = $n;
    }

    public function getValue() : int
    {
        return $this->n;
    }
}
