<?php

namespace spec\Agilap\Qud;

use Agilap\Qud\JobRecord;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JobRecordSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(JobRecord::class);
    }

    function it_has_a_table()
    {
        $this->table()->shouldReturn('braid_jobs');
    }
}
