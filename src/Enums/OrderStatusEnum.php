<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case NotSent = "Not sent";
    case Sent = "Sent";
    case Delivered = "Delivered";

    public function label(): string
    {
        return match($this) {
            self::NotSent => 'Not sent',
            self::Sent => 'Sent',
            self::Delivered => 'Delivered',
        };
    }
}
