<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum UrlOnlineStatus: string implements HasLabel, HasColor, HasIcon
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case UNKNOWN = 'unknown';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ONLINE => 'Online',
            self::OFFLINE => 'Offline',
            self::UNKNOWN => 'Unknown',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::ONLINE => 'success',
            self::OFFLINE => 'danger',
            self::UNKNOWN => 'warning',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::ONLINE => Heroicon::Wifi,
            self::OFFLINE => Heroicon::OutlinedXCircle,
            self::UNKNOWN => Heroicon::OutlinedQuestionMarkCircle,
        };
    }
}
