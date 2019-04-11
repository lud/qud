<?php

namespace spec\Agilap\Qud;

use Agilap\Qud\FixedJobCount;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FixedJobCountSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType(FixedJobCount::class);
    }

    function it_has_a_value()
    {
        $this->beConstructedWith(10);
        $this->getValue()->shouldReturn(10);
    }
}
