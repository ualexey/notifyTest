<?php

namespace App\Domain\Notification;

use App\Domain\Command\NotificationCommand;

interface NotificationAdapterInterface
{
    public function sendNotification(NotificationCommand $command): bool;
}
