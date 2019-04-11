<?php

namespace Agilap\Qud;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;

class Runner
{

    private $handlers = [];
    private $io;

    public function __construct(OutputInterface $output = null)
    {
        $output = $output ?? new NullOutput();
        $this->io = new SymfonyStyle(new ArrayInput([]), $output);
    }

    public function addHandler(JobHandlerInterface $handler)
    {
        $this->handlers[] = $handler;
        return $this;
    }

    public function run(QueueInterface $queue, $opts = [])
    {
        $this->text("Running queue %s", get_class($queue));
        $maxRun = $opts['maxRun'] ?? null;
        $io = $opts['io'] ?? null;
        if (null === $maxRun) {
            while($processed = $this->next($queue));
        } else {
            while($maxRun-- > 0 && $processed = $this->next($queue));
        }
        return true;
    }

    public function next(QueueInterface $queue)
    {
        return $queue->inject(function(JobInterface $job) {
            $this->text("Running next job %s", get_class($job));
            return $this->processJob($job);
        });
    }

    public function processJob(JobInterface $job)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->canHandleJob($job)) {
                $this->text("Using handler %s", get_class($handler));
                return $handler->handleJob($job);
            }
        }
        throw new \Exception("No handler found");
    }

    private function text($message, ...$replaces)
    {
        $this->io->text(sprintf($message, ...$replaces));
    }
}
