Feature: Run queue
    This feature describes the ability to run the jobs from a queue.
    
    Scenario: Enqueue some jobs
        Given there is a database job queue
        And there is a job of type increment and value 2
        And there is a test job handler with a value of 0
        And there is a queue runner
        And we add the test job handler to the queue runner
        When we enqueue the current job
        Then the queue has an unknown count
        When we run the queue runner
        Then the queue has an unknown count
        Then the test job handler value is 2
