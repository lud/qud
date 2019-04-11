<?php

namespace Agilap\Qud;

interface JobHandlerInterface
{
    public function canHandleJob(JobInterface $job) : bool;
    public function handleJob(JobInterface $job) : JobResult;
}
