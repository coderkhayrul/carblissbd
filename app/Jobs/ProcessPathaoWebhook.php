<?php

namespace App\Jobs;

use App\Services\PathaoWebhookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPathaoWebhook implements ShouldQueue
{
    use Queueable;
    public int $timeout = 60;
    public int $tries = 3;
    public function __construct(public int $eventId) {}
    public function handle(PathaoWebhookService $service): void { $service->process($this->eventId); }
}
