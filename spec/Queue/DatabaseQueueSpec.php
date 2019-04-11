<?php

namespace spec\Agilap\Qud\Queue;

use Agilap\Qud\{JobInterface, JobCount, JobResult, JobRecord};
use Agilap\Qud\Queue\DatabaseQueue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseQueueSpec extends ObjectBehavior
{

    private function configureMapper($mapper)
    {
        $mapper->beADoubleOf('\Spot\Mapper');
        return $mapper;
    }

    function it_is_initializable($mapper)
    {
        $this->beConstructedWith($this->configureMapper($mapper));
        $this->shouldHaveType(DatabaseQueue::class);
    }

    function it_can_be_pushed_a_job(JobInterface $job, $mapper)
    {
        $this->beConstructedWith($this->configureMapper($mapper));
        $mapper->create(Argument::type('array'))->willReturn(true);
        $this->push($job)->shouldReturn(true);
    }

    function it_has_a_count(JobInterface $job, $mapper)
    {
        $this->beConstructedWith($this->configureMapper($mapper));
        $this->getCount()->shouldReturnAnInstanceOf(JobCount::class);
    }

    function it_can_inject_jobs(JobInterface $job, $mapper)
    {
        $this->beConstructedWith($this->configureMapper($mapper));
        // no job, should return false
        $this->inject(function(JobInterface $job) {
            throw new \Exception("Should not be called");
        })->shouldReturn(false);
        // push job, inject should return true
        $mapper->create(Argument::type('array'))->willReturn(true);
        $this->push($job);
        // call for reserving a job
        $mapper->query(Argument::type('string'), Argument::type('array'))->willReturn(null)->shouldBeCalled();
        $record = new JobRecord(['serialized_job' => serialize($job->getWrappedObject())]);
        $mapper->first(Argument::type('array'))->willReturn($record)->shouldBeCalled();
        $this->inject(function(JobInterface $job) {
            $called = true;
            return new JobResult(true);
        })->shouldReturn(true);
    }
}
