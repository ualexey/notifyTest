<?php

namespace App\Infrastructure\Notification\Adapter;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\NotificationAdapterInterface;

class AnotherSmsProviderAdapter implements NotificationAdapterInterface
{
    public function sendNotification(NotificationCommand $command): bool
    {
        // Implementation to send SMS using another provider
        return true;
    }
}

