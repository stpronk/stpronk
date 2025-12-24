<?php

namespace Stpronk\Purchases\Enums;

enum PurchaseItemPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case EXTREME = 'extreme';

    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::EXTREME => 'Extreme',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'info',
            self::HIGH => 'warning',
            self::EXTREME => 'danger',
        };
    }
}
