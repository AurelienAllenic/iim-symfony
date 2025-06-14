<?php

namespace App\Message;

class SendNotificationMessage
{
    public function __construct(
        private string $label
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }
}
