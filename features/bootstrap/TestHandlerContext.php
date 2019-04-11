 S<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Assert\Assertion as Assertion;

use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Defines application features from the specific context.
 */
class TestHandlerContext implements Context
{

    private $jobHandler;
    private $currentJob;
    private $runner;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given there is a test job handler with a value of :value
     */
    public function thereIsATestJobHandlerWithAValueOf(int $value)
    {
        $this->jobHandler = new Agilap\Qud\Test\JobHandler\IncrementHandler($value);
    }

    /**
     * @Given there is a job of type increment and value :int
     */
    public function thereIsAJobOfTypeIncrementAndValue(int $int)
    {
        $this->currentJob = new Agilap\Qud\Test\Job\Increment($int);
    }

    /**
     * @When the test job handler handles the current job
     */
    public function theTestJobHandlerHandlesTheJob()
    {
        $this->jobHandler->handleJob($this->currentJob);
    }

    /**
     * @Then the test job handler value is :value
     */
    public function theTestJobHandlerValueIs(int $value)
    {
        Assertion::eq($this->jobHandler->getValue(), $value);
    }

    /**
     * @Given there is a :queueType job queue
     */
    public function thereIsAnInMemoryJobQueue($queueType)
    {
        switch ($queueType) {
            case 'memory':
                $queue = new Agilap\Qud\Queue\MemoryQueue;
                break;
            case 'database':
                $queue = $this->createDatabaseQueue();
                break;
            default:
                throw new \Exception("Unknown queue type '$queueType'");
                break;
        }
        $this->queue = $queue;
    }

    private function createDatabaseQueue()
    {
        $sqlitePath = realpath(__DIR__ . '/../../var/cache/test/db') . '/test.db';
        echo "Delete previously used database $sqlitePath\n";
        @unlink($sqlitePath);
        echo sprintf("Use database %s\n", $sqlitePath);
        $dsn = 'sqlite:' . $sqlitePath;
        $pdo = new \PDO($dsn, null, null, [
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
        $db = new \Spot\Locator(new \Spot\Config());
        $db->config()->addConnection('testdb', ['pdo' => $pdo]);
        $mapper = $db->mapper(\Agilap\Qud\JobRecord::class);
        $mapper->migrate();
        return new Agilap\Qud\Queue\DatabaseQueue($mapper);
    }

    /**
     * @When we enqueue the current job
     */
    public function weEnqueueTheCurrentJob()
    {
        $this->queue->push($this->currentJob);
    }

    /**
     * @Then the queue has a count of :count
     */
    public function theQueueHasACountOf($count)
    {
        Assertion::eq($this->queue->getCount()->isKnown(), true);
        Assertion::eq($this->queue->getCount()->getValue(), $count);
    }

    /**
     * @Then the queue has an unknown count
     */
    public function theQueueHasAnUnknownCount()
    {
        Assertion::eq($this->queue->getCount()->isKnown(), false);
    }    

    /**
     * @Given there is a queue runner
     */
    public function thereIsAQueueRunner()
    {
        $output = new ConsoleOutput();
        $this->runner = new Agilap\Qud\Runner($output);
    }

    /**
     * @Given we add the test job handler to the queue runner
     */
    public function weAddTheTestJobHandlerToTheQueueRunner()
    {
        $this->runner->addHandler($this->jobHandler);
    }

    /**
     * @When we run the queue runner
     */
    public function weRunTheQueueRunner()
    {
        $this->runner->run($this->queue);
    }
}
