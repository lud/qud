<?php

namespace Agilap\Qud;

interface QueueInterface
{
    public function push(JobInterface $job) : bool;
    public function inject(callable $processJob);
    public function getCount() : JobCount;
}
