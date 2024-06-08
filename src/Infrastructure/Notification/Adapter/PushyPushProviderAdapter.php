<?php

namespace App\Infrastructure\Notification\Adapter;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\NotificationAdapterInterface;

class PushyPushProviderAdapter implements NotificationAdapterInterface
{
    public function sendNotification(NotificationCommand $command): bool
    {
        // Implementation to send Push
        return true;
    }
}

