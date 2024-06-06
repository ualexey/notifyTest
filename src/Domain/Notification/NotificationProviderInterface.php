<?php

namespace App\Domain\Notification;

interface NotificationProviderInterface
{
    public function getProviders(): ?array;
}
