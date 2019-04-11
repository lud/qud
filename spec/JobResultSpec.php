<?php

namespace spec\Agilap\Qud;

use Agilap\Qud\JobResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JobResultSpec extends ObjectBehavior
{
    function it_can_be_successful()
    {
        $this->beConstructedWith(true);
        $this->shouldHaveType(JobResult::class);
        $this->isSuccessful()->shouldReturn(true);
    }

    function it_can_fail()
    {
        $this->beConstructedWith(false);
        $this->shouldHaveType(JobResult::class);
        $this->isSuccessful()->shouldReturn(false);
    }
}
