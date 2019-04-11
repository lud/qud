<?php

namespace Agilap\Qud\Queue;
use Agilap\Qud\{
    JobInterface,
    JobInjectorTrait,
    JobCount,
    FixedJobCount,
    QueueInterface
};

class MemoryQueue implements QueueInterface
{
    use JobInjectorTrait;

    private $queue = [];

    // pushes new jobs on nextQueue so current job is always end(queue)
    public function push(JobInterface $job) : bool
    {
        $this->queue[] = $job;
        return true;
    }

    public function getCount() : JobCount
    {
        return new FixedJobCount(count($this->queue));
    }

    public function next() : ?JobInterface
    {
        return array_pop($this->queue);
    }

    public function success()
    {
    }

    public function failure()
    {
    }
}

