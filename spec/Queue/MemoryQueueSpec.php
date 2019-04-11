<?php

namespace spec\Agilap\Qud\Queue;

use Agilap\Qud\Queue\MemoryQueue;
use Agilap\Qud\{JobInterface, JobCount, JobResult};
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemoryQueueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MemoryQueue::class);
    }

    function it_can_be_pushed_a_job(JobInterface $job)
    {
        $this->push($job)->shouldReturn(true);
    }

    function it_has_a_count(JobInterface $job)
    {
        $this->getCount()->shouldReturnAnInstanceOf(JobCount::class);
    }

    function it_can_inject_jobs(JobInterface $job)
    {
        // no job, should return false
        $this->inject(function(JobInterface $job) {
            throw new \Exception("Should not be called");
        })->shouldReturn(false);
        // push job, inject should return true
        $this->push($job);
        $this->inject(function(JobInterface $job) {
            $called = true;
            return new JobResult(true);
        })->shouldReturn(true);
    }
}
