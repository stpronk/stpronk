<?php

namespace Stpronk\Purchases\Enums;

enum PurchaseItemStatus: string
{
    case NEED = 'need';
    case SHIPPED_OR_BOUGHT = 'shipped_or_bought';
    case DONE = 'done';

    public function getLabel(): string
    {
        return match ($this) {
            self::NEED => 'Need',
            self::SHIPPED_OR_BOUGHT => 'Shipped or Bought',
            self::DONE => 'Done',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NEED => 'danger',
            self::SHIPPED_OR_BOUGHT => 'warning',
            self::DONE => 'success',
        };
    }
}
