<?php

namespace App\Services\Local\Helpers;

class QueueNameHelper
{
    public static function job(string $jobClassName): string
    {
        return config('queue.names.jobs.' . $jobClassName);
    }
}
