<?php

namespace App\Domain\Handler;

interface CliHandlerInterface
{
    public function handle(string $userId): void;
}
