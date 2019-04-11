<?php

namespace Agilap\Qud;

final class JobResult
{

    private $isSuccessful;
    private $error;

    public function __construct(bool $isSuccessful, \Throwable $error = null)
    {
        $this->isSuccessful = $isSuccessful;
        $this->error = $error;
    }

    public static function success()
    {
        return new static(true);
    }

    public static function error(\Throwable $error)
    {
        return new static(false, $error);
    }

    public function isSuccessful()
    {
        return $this->isSuccessful;
    }

    public function getError()
    {
        return $this->error;
    }
}
