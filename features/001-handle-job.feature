Feature: Handle jobs
    This feature describes the base interface of a job-handler
    (which in this case keeps state) and jobs.
    The handler will be given two jobs and must handle them.

    Scenario: Enqueue some jobs
        Given there is a test job handler with a value of 0
        And there is a job of type increment and value 2
        When the test job handler handles the current job
        Then the test job handler value is 2
        Given there is a job of type increment and value 10
        When the test job handler handles the current job
        Then the test job handler value is 12
