<?php

namespace App\Tests\Controller;

use App\Application\Service\NotificationInfoService;
use App\Application\Service\NotificationService;
use App\Controller\NotificationController;
use App\Domain\Command\NotificationCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationControllerTest extends TestCase
{
    private NotificationService $notificationServiceMock;
    private NotificationInfoService $notificationInfoServiceMock;
    private NotificationController $notificationController;

    protected function setUp(): void
    {
        $this->notificationServiceMock = $this->createMock(NotificationService::class);
        $this->notificationInfoServiceMock = $this->createMock(NotificationInfoService::class);

        $this->notificationController = new NotificationController(
            $this->notificationServiceMock,
            $this->notificationInfoServiceMock
        );
    }

    public function testSendNotificationWithValidData(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'userId' => '123456',
            'type' => 'sms',
            'message' => 'Test message',
            'recipient' => 'test@example.com',
        ]));

        $this->notificationServiceMock->expects($this->once())
            ->method('send')
            ->with(new NotificationCommand('123456', 'sms', 'Test message', 'test@example.com', null));

        $response = $this->notificationController->sendNotification($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testSendNotificationWithInvalidData(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'type' => 'sms',
            'message' => 'Test message',
            'recipient' => 'test@example.com',
        ]));

        $response = $this->notificationController->sendNotification($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testGetNotificationsWithValidUserId(): void
    {
        $request = new Request([], [], ['user_id' => '123']);

        $this->notificationInfoServiceMock->expects($this->once())
            ->method('getNotifications')
            ->with('123')
            ->willReturn(['notification1', 'notification2']);

        $response = $this->notificationController->getNotifications($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('notifications', json_decode($response->getContent(), true));
    }

    public function testGetNotificationsWithInvalidUserId(): void
    {
        $request = new Request();

        $response = $this->notificationController->getNotifications($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
