<?php

namespace Agilap\Qud\Queue;
use Agilap\Qud\{
    JobInterface,
    JobInjectorTrait,
    JobCount,
    UnknownJobCount,
    JobRecord,
    QueueInterface
};

class DatabaseQueue implements QueueInterface
{
    use JobInjectorTrait;

    protected $mapper;
    protected $workerId;

    const JOB_STATUS_PENDING = 0;
    const JOB_STATUS_SUCCESS = 1;
    const JOB_STATUS_FAILED = 2;

    public function __construct(\Spot\Mapper $mapper)
    {
        $this->mapper = $mapper;
        $this->workerId = uniqid('worker-');
    }

    public function push(JobInterface $job) : bool
    {
        $serialized = serialize($job);
        $attrs = [
            'serialized_job' => $serialized,
            'status' => self::JOB_STATUS_PENDING,
            'worker_id' => null, // no owner process for now
        ];
        return !!$this->mapper->create($attrs);
    }

    public function next() : ?JobInterface
    {
        // - We will assign our worker_id to a job from the database
        // - We will select it and feed it to the runner
        // - On result, we will update the status
        $this->reserveJob();
        return $this->findAssignedJob();
    }

    private function findAssignedJob()
    {
        $record = $this->mapper->first([
            'worker_id' => $this->workerId,
            'status' => self::JOB_STATUS_PENDING,
        ]);
        return $record 
            ? $this->unpackJob($record)
            : null;
    }

    protected function unpackJob(JobRecord $record)
    {
        $job = unserialize($record->serialized_job);
        $job->setMeta('db_id', $record->id);
        return $job;
    }

    private function reserveJob()
    {
        // By default, SQLite is not compiled with support for UPDATE ... LIMIT
        // So we use a sub query

        $table = JobRecord::table();
        $sql = "
            UPDATE $table SET
                worker_id = :worker_id
            WHERE id IN (
                SELECT id FROM $table
                WHERE worker_id is null
                  AND status = :status
                LIMIT 1
            )
        ";
        $sql = explode("\n", $sql);
        $sql = array_map('trim', $sql);
        $sql = implode(' ', $sql);
        $this->mapper->query($sql, [
            'worker_id' => $this->workerId,
            'status' => self::JOB_STATUS_PENDING,
        ]);
    }

    protected function success(JobInterface $job)
    {
        $this->updateStatus($job->getMeta('db_id'), self::JOB_STATUS_SUCCESS);
    }

    protected function failure(JobInterface $job)
    {
        $this->updateStatus($job->getMeta('db_id'), self::JOB_STATUS_FAILURE);
    }

    private function updateStatus($id, $status)
    {
        $table = JobRecord::table();
        $sql = "UPDATE $table SET status = :status WHERE id = :id";
        $this->mapper->query($sql, [
            'id' => $id,
            'status' => $status,
        ]);
    }

    public function getCount() : JobCount
    {
        return new UnknownJobCount;
    }

}
