<?php

namespace SwagProductDiscount\Event\SwagProductDiscountTranslation;

use Shopware\Api\Write\WrittenEvent;
use SwagProductDiscount\Definition\SwagProductDiscountTranslationDefinition;

class SwagProductDiscountTranslationWrittenEvent extends WrittenEvent
{
    const NAME = 'swag_product_discount_translation.written';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDefinition(): string
    {
        return SwagProductDiscountTranslationDefinition::class;
    }
}