<?php

namespace App\Application\Service;

use App\Entity\NotificationLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class NotificationInfoService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getNotifications(string $userId): array
    {
        $notificationRepository = $this->entityManager->getRepository(NotificationLog::class);
        $notificationLogs = $notificationRepository->findBy(['userId' => $userId]);

        if (empty($notificationLogs)) {
            throw new EntityNotFoundException("No notifications found for user ID {$userId}");
        }

        return array_map([$this, 'formatData'], $notificationLogs);
    }

    private function formatData(NotificationLog $notificationLog): array
    {
        return [
            'id' => $notificationLog->getId(),
            'user_id' => $notificationLog->getUserId(),
            'updated_on' => $notificationLog->getUpdatedOn()->format('Y-m-d H:i:s'),
            'status' => $notificationLog->getStatus(),
            'recipient' => $notificationLog->getRecipient(),
            'channel' => $notificationLog->getChannel(),
            'provider' => $notificationLog->getProvider(),
        ];
    }
}
