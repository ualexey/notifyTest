<?php

namespace App\Domain\Service;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\NotificationProviderInterface;
use App\Domain\Service\NotificationLogger;
use App\Entity\NotificationLog;
use Exception;

class NotificationSender
{
    private NotificationLogger $notificationLogger;

    public function __construct(NotificationLogger $notificationLogger)
    {
        $this->notificationLogger = $notificationLogger;
    }

    public function send(NotificationCommand $command, ?NotificationProviderInterface $notificationTypeProviders): void
    {
        try {
            if (!$notificationTypeProviders) {
                throw new Exception("No providers found for {$command->getType()} channel");
            }
            $providers = $notificationTypeProviders->getProviders();

            foreach ($providers as $provider) {
                if ($provider->sendNotification($command)) {
                    // Log success attempt
                    $this->notificationLogger->log($command, get_class($provider), NotificationLog::STATUS_SUCCESS);
                    return;
                }
            }

            $this->notificationLogger->log($command, null, NotificationLog::STATUS_DELAYED);
            throw new Exception("Notification is delayed and would be sent later");

        } catch (Exception $e) {
            // Log the exception
            $this->notificationLogger->log($command, null, NotificationLog::STATUS_FAILED);
            throw $e;
        }
    }
}
