<?php

namespace spec\Agilap\Qud;

use Agilap\Qud\UnknownJobCount;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UnknownJobCountSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UnknownJobCount::class);
    }

    function it_has_unknown_count()
    {
        $this->isKnown()->shouldReturn(false);
    }
}
