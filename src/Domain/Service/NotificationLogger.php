<?php

namespace App\Domain\Service;

use App\Domain\Command\NotificationCommand;
use App\Entity\NotificationLog;
use Doctrine\ORM\EntityManagerInterface;

class NotificationLogger
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function log(NotificationCommand $command, ?string $provider, string $status): void
    {
        $notificationLog = null;

        if ($command->getNotificationId() != null) {
            $notificationRepository = $this->entityManager->getRepository(NotificationLog::class);
            $notificationLog = $notificationRepository->findOneBy(['id' => $command->getNotificationId()]);
        }

        if (!$notificationLog) {
            $notificationLog = new NotificationLog();
            $notificationLog->setUserId($command->getUserId());
            $notificationLog->setCreatedOn(new \DateTime());
            $notificationLog->setRecipient($command->getRecipient());
            $notificationLog->setChannel($command->getType());
            $notificationLog->setSubject($command->getSubject());
            $notificationLog->setBody($command->getMessage());
        }

        $notificationLog->setUpdatedOn(new \DateTime());
        $notificationLog->setStatus($status);
        $notificationLog->setProvider($provider);

        $this->entityManager->persist($notificationLog);
        $this->entityManager->flush();
    }

}
