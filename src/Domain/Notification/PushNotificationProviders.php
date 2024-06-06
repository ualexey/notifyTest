<?php

namespace App\Domain\Notification;

class PushNotificationProviders implements NotificationProviderInterface
{
    private array $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function getProviders(): ?array
    {
        return $this->providers;
    }
}

