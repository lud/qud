<?php

namespace spec\Agilap\Qud\Test\JobHandler;

use Agilap\Qud\Test\JobHandler\IncrementHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Agilap\Qud\{JobInterface, JobResult};
use Agilap\Qud\Test\Job\Increment;

class IncrementHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IncrementHandler::class);
    }

    function it_can_handle_a_job()
    {
        $job = new Increment(1);
        $this->canHandleJob($job)->shouldReturn(true);
        $this->handleJob($job)->shouldReturnAnInstanceOf(JobResult::class);
    }

    function it_can_give_its_value()
    {
        $this->beConstructedWith(100);
        $this->getValue()->shouldReturn(100);
    }
}
