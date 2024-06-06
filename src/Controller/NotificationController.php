<?php

namespace App\Controller;

use App\Application\Service\NotificationInfoService;
use App\Application\Service\NotificationService;
use App\Domain\Command\NotificationCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    private NotificationService $notificationService;
    private NotificationInfoService $notificationInfoService;

    public function __construct(NotificationService $notificationService, NotificationInfoService $notificationInfoService)
    {
        $this->notificationService = $notificationService;
        $this->notificationInfoService = $notificationInfoService;
    }

    #[Route('/notification/send', name: 'send-notification', methods: ['POST'])]
    public function sendNotification(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['userId'], $data['type'], $data['message'], $data['recipient'])) {
            return new JsonResponse(['error' => 'Invalid request data'], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['userId'];
        $type = $data['type'];
        $message = $data['message'];
        $recipient = $data['recipient'];
        $subject = $data['subject'] ?? null;

        try {
            $this->notificationService->send(new NotificationCommand($userId, $type, $message, $recipient, $subject));

            return new JsonResponse(['message' => 'Notification sent successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to send notification:', $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/notification/get/{user_id}', name: 'get-notification', methods: ['GET'])]
    public function getNotifications(Request $request): Response
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return new JsonResponse(['message' => 'Invalid user_id'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $notifications = $this->notificationInfoService->getNotifications($userId);
            return new JsonResponse(['notifications' => $notifications], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Failed: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
