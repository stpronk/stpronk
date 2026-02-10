<?php

namespace Stpronk\Aeries\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum GoodsEntityType: string implements HasLabel, HasColor
{
    case FDM_PRINT = 'FDM-Print';
    case THIRD_PARTY_PRODUCT = '3RD-Party Product';
    case DIGITAL_MODEL = 'Digital Model';
    case SERVICE = 'Service';
    case THREE_D_MODEL = '3D-Model';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FDM_PRINT => __('stpronk-aeries::general.enums.goods_entity_type.fdm_print'),
            self::THIRD_PARTY_PRODUCT => __('stpronk-aeries::general.enums.goods_entity_type.third_party_product'),
            self::DIGITAL_MODEL => __('stpronk-aeries::general.enums.goods_entity_type.digital_model'),
            self::SERVICE => __('stpronk-aeries::general.enums.goods_entity_type.service'),
            self::THREE_D_MODEL => __('stpronk-aeries::general.enums.goods_entity_type.3d_model'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::FDM_PRINT => 'primary',
            self::THIRD_PARTY_PRODUCT => 'secondary',
            self::DIGITAL_MODEL => 'success',
            self::SERVICE => 'gray',
            self::THREE_D_MODEL => 'gray',
        };
    }
}
