<?php

namespace App\Domain\Notification;

class SmsNotificationProviders implements NotificationProviderInterface
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
