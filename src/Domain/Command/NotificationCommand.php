<?php

namespace App\Domain\Command;

class NotificationCommand
{
    private string $userId;
    private string $message;
    private string $recipient;
    private string $type;
    private ?string $subject;
    private ?int $notificationId;

    public function __construct(
        string  $userId,
        string  $type,
        string  $message,
        string  $recipient,
        ?string $subject = null,
        ?int    $notificationId = null,
    )
    {

        $this->userId = $userId;
        $this->type = $type;
        $this->message = $message;
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->notificationId = $notificationId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject;
    }

    public function getNotificationId(): ?int
    {
        return $this->notificationId;
    }

    public function setNotificationId(int $notificationId): void
    {
        $this->notificationId;
    }

}

