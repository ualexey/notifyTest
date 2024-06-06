<?php

namespace App\Tests\Application\Service;

use App\Application\Service\NotificationInfoService;
use App\Entity\NotificationLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class NotificationInfoServiceTest extends TestCase
{
    public function testGetNotificationsReturnsFormattedNotifications()
    {
        $userId = '123';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(EntityRepository::class);

        $notificationLog = new NotificationLog();
        $notificationLog->setUserId($userId);
        $notificationLog->setUpdatedOn(new \DateTime());
        $notificationLog->setStatus('pending');
        $notificationLog->setRecipient('example@test.com');
        $notificationLog->setChannel('email');
        $notificationLog->setProvider('some_provider');
        $notificationLog->setSubject('subject');
        $notificationLog->setBody('body');

        $repository->expects($this->once())
            ->method('findBy')
            ->with(['userId' => $userId])
            ->willReturn([$notificationLog]);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(NotificationLog::class)
            ->willReturn($repository);

        $notificationInfoService = new NotificationInfoService($entityManager);

        $result = $notificationInfoService->getNotifications($userId);

        $this->assertCount(1, $result);
        $this->assertEquals($userId, $result[0]['user_id']);
    }

    public function testGetNotificationsThrowsExceptionWhenNoNotificationsFound()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No notifications found for user ID 123");

        $userId = '123';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(EntityRepository::class);

        $repository->expects($this->once())
            ->method('findBy')
            ->with(['userId' => $userId])
            ->willReturn([]);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(NotificationLog::class)
            ->willReturn($repository);

        $notificationInfoService = new NotificationInfoService($entityManager);
        
        $notificationInfoService->getNotifications($userId);
    }
}
