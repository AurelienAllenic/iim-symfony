<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
class SendAdminNotification
{
    public function __construct(
        private readonly string $actionType,
        private readonly string $entityName,
        private readonly \DateTimeImmutable $actionDate,
        private readonly int $adminId
    ) {
    }

    // Getters...
}
