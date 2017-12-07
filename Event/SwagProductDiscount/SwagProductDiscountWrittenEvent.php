<?php

namespace SwagProductDiscount\Event\SwagProductDiscount;

use Shopware\Api\Write\WrittenEvent;
use SwagProductDiscount\Definition\SwagProductDiscountDefinition;

class SwagProductDiscountWrittenEvent extends WrittenEvent
{
    const NAME = 'swag_product_discount.written';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDefinition(): string
    {
        return SwagProductDiscountDefinition::class;
    }
}