<?php

namespace spec\Agilap\Qud;

use Agilap\Qud\{Runner, JobHandlerInterface, QueueInterface, JobInterface, JobResult};
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RunnerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Runner::class);
    }

    function it_can_receive_handlers(JobHandlerInterface $handler)
    {
        $this->addHandler($handler)->shouldReturn($this);
    }

    function it_can_run_a_queue(QueueInterface $queue)
    {
        $this->run($queue)->shouldReturn(true);
    }

    function it_can_process_a_job(JobHandlerInterface $handler, JobInterface $job)
    {
        $this->addHandler($handler);
        $handler->handleJob($job)->willReturn(new JobResult(true));
        $handler->canHandleJob($job)->willReturn(true);
        $this->processJob($job)->shouldReturnAnInstanceOf(JobResult::class);
    }
}
