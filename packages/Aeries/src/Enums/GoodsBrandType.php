<?php

namespace Stpronk\Aeries\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum GoodsBrandType: string implements HasLabel, HasColor
{
    case PHYSICAL = 'Physical';
    case THREE_D_MODELS = '3D-Models';
    case DIGITAL = 'Digital';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PHYSICAL => __('stpronk-aeries::general.enums.goods_brand_type.physical'),
            self::THREE_D_MODELS => __('stpronk-aeries::general.enums.goods_brand_type.3d_models'),
            self::DIGITAL => __('stpronk-aeries::general.enums.goods_brand_type.digital'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PHYSICAL => 'info',
            self::THREE_D_MODELS => 'warning',
            self::DIGITAL => 'success',
        };
    }
}
