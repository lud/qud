Feature: Queue jobs
    This feature describes the ability to enqueue jobs in a job-queue.
    
    Scenario: Enqueue some jobs
        Given there is a memory job queue
        And there is a job of type increment and value 2
        When we enqueue the current job
        Then the queue has a count of 1
