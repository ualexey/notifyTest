<?php

namespace App\Tests\Domain\Service;

use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\NotificationAdapterInterface;
use App\Domain\Notification\NotificationProviderInterface;
use App\Domain\Service\NotificationLogger;
use App\Domain\Service\NotificationSender;
use App\Entity\NotificationLog;
use PHPUnit\Framework\TestCase;

class NotificationSenderTest extends TestCase
{
    private NotificationLogger $notificationLoggerMock;
    private NotificationProviderInterface $notificationProviderMock;
    private NotificationSender $notificationSender;

    protected function setUp(): void
    {
        $this->notificationLoggerMock = $this->createMock(NotificationLogger::class);
        $this->notificationProviderMock = $this->createMock(NotificationProviderInterface::class);
        $this->notificationSender = new NotificationSender($this->notificationLoggerMock);
    }

    public function testSendThrowsExceptionWhenNoProvidersFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No providers found for sms channel');

        $command = new NotificationCommand(123, 'sms', 'Test message', 'test@example.com');

        $this->notificationSender->send($command, null);
    }

    public function testSendLogsSuccessWhenProviderSucceeds(): void
    {
        $command = new NotificationCommand(123, 'email', 'Test message', 'test@example.com');

        $providerAdapterMock = $this->createMock(NotificationAdapterInterface::class);
        $providerAdapterMock->method('sendNotification')->willReturn(true);

        $this->notificationProviderMock->method('getProviders')
            ->willReturn([$providerAdapterMock]);

        $this->notificationLoggerMock->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo($command),
                $this->equalTo(get_class($providerAdapterMock)),
                $this->equalTo(NotificationLog::STATUS_SUCCESS)
            );

        $this->notificationSender->send($command, $this->notificationProviderMock);
    }

}
