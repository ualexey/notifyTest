<?php

namespace App\Infrastructure\Notification\Adapter;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\NotificationAdapterInterface;
use Aws\Ses\SesClient;

class AwsSesEmailProviderAdapter implements NotificationAdapterInterface
{
    private SesClient $sesClient;

    public function __construct(SesClient $sesClient)
    {
        $this->sesClient = $sesClient;
    }

    public function sendNotification(NotificationCommand $command): bool
    {
        try {
            $this->sesClient->sendEmail([
                'Source' => 'your-email@example.com',
                'Destination' => [
                    'ToAddresses' => [$command->getRecipient()],
                ],
                'Message' => [
                    'Subject' => ['Data' => $command->getSubject()],
                    'Body' => ['Text' => ['Data' => $command->getMessage()]],
                ],
            ]);
            return true;
        } catch (\Exception $e) {
            // Log the exception
            return false;
        }
    }
}
