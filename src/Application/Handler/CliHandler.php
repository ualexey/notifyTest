<?php

namespace App\Application\Handler;

use App\Application\Service\NotificationService;
use App\Domain\Command\NotificationCommand;
use App\Domain\Handler\CliHandlerInterface;
use App\Entity\NotificationLog;
use Doctrine\ORM\EntityManagerInterface;

class CliHandler implements CliHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private NotificationService $notificationService;

    public function __construct(EntityManagerInterface $entityManager, NotificationService $notificationService)
    {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
    }

    public function handle(string $userId): void
    {
        $notificationRepository = $this->entityManager->getRepository(NotificationLog::class);
        $notifications = $notificationRepository->findBy([
            'userId' => $userId,
            'status' => NotificationLog::STATUS_DELAYED
        ], [
            'id' => 'ASC'
        ]);

        foreach ($notifications as $notification) {
            $command = new NotificationCommand(
                $notification->getUserId(),
                $notification->getChannel(),
                $notification->getBody(),
                $notification->getRecipient(),
                $notification->getSubject(),
                $notification->getId()
            );
            $this->notificationService->send($command);
        }
    }
}
