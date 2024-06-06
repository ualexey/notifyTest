<?php

namespace App\Infrastructure\Notification\Adapter;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\NotificationAdapterInterface;

class AnotherEmailProviderAdapter implements NotificationAdapterInterface
{
    public function sendNotification(NotificationCommand $command): bool
    {
        // Implementation to send Email using another provider
        return true;
    }
}

