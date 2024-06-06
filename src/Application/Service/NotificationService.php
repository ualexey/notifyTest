<?php

namespace App\Application\Service;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\EmailNotificationProviders;
use App\Domain\Notification\SmsNotificationProviders;
use App\Domain\Notification\NotificationProviderInterface;
use App\Domain\Service\NotificationSender;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NotificationService
{
    private NotificationSender $notificationSender;
    private SmsNotificationProviders $smsNotificationProviders;
    private EmailNotificationProviders $emailNotificationProviders;
    private ParameterBagInterface $channelStatus;

    public function __construct(
        NotificationSender         $notificationSender,
        SmsNotificationProviders   $smsNotificationProviders,
        EmailNotificationProviders $emailNotificationProviders,
        ParameterBagInterface      $channelStatus,
    )
    {
        $this->notificationSender = $notificationSender;
        $this->smsNotificationProviders = $smsNotificationProviders;
        $this->emailNotificationProviders = $emailNotificationProviders;
        $this->channelStatus = $channelStatus;
    }

    public function send(NotificationCommand $command): void
    {
        // Determine the channel of notification (SMS, Email, etc.)
        $channelProviders = $this->getChannelProviders($command->getType());
        $this->notificationSender->send($command, $channelProviders);
    }

    private function getChannelProviders(string $type): NotificationProviderInterface
    {
        if ($type === 'sms' && $this->channelStatus->get('enable_sms')) {
            return $this->smsNotificationProviders;
        } elseif ($type === 'email' && $this->channelStatus->get('enable_email')) {
            return $this->emailNotificationProviders;
        } elseif ($type === 'push' && $this->channelStatus->get('enable_push')) {
            // Return the appropriate push notification provider
        } else {
            throw new \InvalidArgumentException('Invalid or inactive notification type');
        }
        return false;
    }

}
