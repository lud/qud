<?php
namespace Agilap\Qud;

trait JobInjectorTrait {
    public function inject(callable $processJob) : bool
    {
        $job = $this->next();
        if ($job) {
            if ($processJob($job)->isSuccessful()) {
                $this->success($job);
            } else {
                $this->failure($job);
            }
            return true;
        } else {
            return false;
        }
    }
    abstract protected function next() : ?JobInterface;
    abstract protected function success(JobInterface $job) : void;
    abstract protected function failure(JobInterface $job) : void;
}
