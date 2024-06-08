<?php

namespace App\Infrastructure\Notification\Adapter;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\NotificationAdapterInterface;
use Twilio\Rest\Client;

//---------------------FYI!---------------------//
// I got "An error occurred when provisioning a number. Please try again."
// when I try to get trial phone number on twilio account console

class TwilioSmsProviderAdapter implements NotificationAdapterInterface
{
    private Client $twilioClient;
    private string $twilioPhone;
    
    public function __construct(Client $twilioClient, string $twilioPhone)
    {
        $this->twilioClient = $twilioClient;
        $this->twilioPhone = $twilioPhone;
    }

    public function sendNotification(NotificationCommand $command): bool
    {
        try {
            $this->twilioClient->messages->create(
                $command->getRecipient(),
                [
                    'from' => $this->twilioPhone,
                    'body' => $command->getMessage(),
                ]
            );
            return true;
        } catch (\Exception $e) {
            // Log the exception
            return false;
        }
    }

}
