<?php

namespace Agilap\Qud\Test\JobHandler;

use Agilap\Qud\{JobHandlerInterface, JobInterface, JobResult};

class IncrementHandler implements JobHandlerInterface
{

    private $value;
    
    public function __construct(int $startValue = 0)
    {
        $this->value = $startValue;
    }

    public function canHandleJob(JobInterface $job) : bool
    {
        return $job instanceof \Agilap\Qud\Test\Job\Increment;
    }

    public function handleJob(JobInterface $job) : JobResult
    {
        $this->value += $job->getValue();
        return JobResult::success();
    }

    public function getValue() : int
    {
        return $this->value;
    }
}
