<?php

namespace spec\Agilap\Qud\Test\Job;

use Agilap\Qud\Test\Job\Increment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IncrementSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType(Increment::class);
    }
}
