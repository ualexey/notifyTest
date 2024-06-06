<?php

namespace App\Tests\Application\Handler;

use App\Application\Handler\CliHandler;
use App\Application\Service\NotificationService;
use App\Domain\Command\NotificationCommand;
use App\Entity\NotificationLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class CliHandlerTest extends TestCase
{
    private EntityManagerInterface $entityManagerMock;
    private NotificationService $notificationServiceMock;
    private EntityRepository $notificationRepositoryMock;
    private CliHandler $cliHandler;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->notificationServiceMock = $this->createMock(NotificationService::class);
        $this->notificationRepositoryMock = $this->createMock(EntityRepository::class);

        $this->entityManagerMock->method('getRepository')
            ->willReturn($this->notificationRepositoryMock);

        $this->cliHandler = new CliHandler($this->entityManagerMock, $this->notificationServiceMock);
    }

    public function testHandleWithDelayedNotifications(): void
    {
        $notification = $this->createMock(NotificationLog::class);
        $notification->method('getUserId')->willReturn('123');
        $notification->method('getChannel')->willReturn('email');
        $notification->method('getBody')->willReturn('Test message');
        $notification->method('getRecipient')->willReturn('test@example.com');
        $notification->method('getSubject')->willReturn('Test subject');
        $notification->method('getStatus')->willReturn(NotificationLog::STATUS_DELAYED);
        $notification->method('getId')->willReturn(1);

        $this->notificationRepositoryMock->method('findBy')
            ->willReturn([$notification]);

        $this->notificationServiceMock->expects($this->once())
            ->method('send')
            ->with($this->callback(function (NotificationCommand $command) use ($notification) {
                return $command->getUserId() === $notification->getUserId() &&
                    $command->getType() === $notification->getChannel() &&
                    $command->getMessage() === $notification->getBody() &&
                    $command->getRecipient() === $notification->getRecipient() &&
                    $command->getSubject() === $notification->getSubject() &&
                    $command->getNotificationId() === $notification->getId();
            }));

        $this->cliHandler->handle('123');
    }

    public function testHandleWithNoDelayedNotifications(): void
    {
        $this->notificationRepositoryMock->method('findBy')
            ->willReturn([]);

        $this->notificationServiceMock->expects($this->never())
            ->method('send');

        $this->cliHandler->handle('123');
    }
}
