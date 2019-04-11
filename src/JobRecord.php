<?php

namespace Agilap\Qud;

class JobRecord extends \Spot\Entity 
{
    protected static $table = 'braid_jobs';

    public static function fields()
    {
        return [
            'id' => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'serialized_job'  => ['type' => 'string', 'required' => true],
            'status'  => ['type' => 'integer', 'required' => true],
            'worker_id' => ['type' => 'string', 'required' => false],
        ];
    }
}
