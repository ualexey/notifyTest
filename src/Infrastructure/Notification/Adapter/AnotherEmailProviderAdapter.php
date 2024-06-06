<?php

// src/Infrastructure/Notification/Adapter/AnotherEmailProviderAdapter.php
namespace App\Infrastructure\Notification\Adapter;

use App\Domain\Notification\EmailProviderInterface;
use AnotherEmailProviderSdk\Client;

class AnotherEmailProviderAdapter implements EmailProviderInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function sendEmail(string $subject, string $message, string $recipient): bool
    {
        try {
            $this->client->sendEmail($recipient, $subject, $message);
            return true;
        } catch (\Exception $e) {
            // Log the exception or handle it accordingly
            return false;
        }
    }
}

