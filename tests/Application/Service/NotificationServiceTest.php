<?php

namespace App\Tests\Application\Service;

use App\Application\Service\NotificationService;
use App\Domain\Command\NotificationCommand;
use App\Domain\Notification\EmailNotificationProviders;
use App\Domain\Notification\SmsNotificationProviders;
use App\Domain\Service\NotificationSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NotificationServiceTest extends TestCase
{
    private NotificationSender $notificationSenderMock;
    private SmsNotificationProviders $smsNotificationProvidersMock;
    private EmailNotificationProviders $emailNotificationProvidersMock;
    private ParameterBagInterface $parameterBagMock;
    private NotificationService $notificationService;

    protected function setUp(): void
    {
        $this->notificationSenderMock = $this->createMock(NotificationSender::class);
        $this->smsNotificationProvidersMock = $this->createMock(SmsNotificationProviders::class);
        $this->emailNotificationProvidersMock = $this->createMock(EmailNotificationProviders::class);
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);

        $this->notificationService = new NotificationService(
            $this->notificationSenderMock,
            $this->smsNotificationProvidersMock,
            $this->emailNotificationProvidersMock,
            $this->parameterBagMock
        );
    }

    public function testSendSmsNotification(): void
    {
        $this->parameterBagMock->expects($this->once())
            ->method('get')
            ->with('enable_sms')
            ->willReturn(true);

        $command = new NotificationCommand('123', 'sms', 'Test message', 'test@example.com');
        $this->notificationSenderMock->expects($this->once())
            ->method('send')
            ->with($command, $this->smsNotificationProvidersMock);

        $this->notificationService->send($command);
    }

    public function testSendEmailNotification(): void
    {
        $this->parameterBagMock->expects($this->once())
            ->method('get')
            ->with('enable_email')
            ->willReturn(true);

        $command = new NotificationCommand('123', 'email', 'Test message', 'test@example.com');
        $this->notificationSenderMock->expects($this->once())
            ->method('send')
            ->with($command, $this->emailNotificationProvidersMock);

        $this->notificationService->send($command);
    }
}
