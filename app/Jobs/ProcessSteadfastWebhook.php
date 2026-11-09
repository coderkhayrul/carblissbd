<?php

namespace App\Jobs;

use App\Services\SteadfastWebhookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessSteadfastWebhook implements ShouldQueue
{
    use Queueable;

    public int $timeout = 60;
    public int $tries = 3;

    public function __construct(public int $eventId) {}

    public function handle(SteadfastWebhookService $service): void
    {
        $service->process($this->eventId);
    }
}
