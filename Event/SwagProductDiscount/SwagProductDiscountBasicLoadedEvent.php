<?php

namespace SwagProductDiscount\Event\SwagProductDiscount;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use SwagProductDiscount\Collection\SwagProductDiscountBasicCollection;


class SwagProductDiscountBasicLoadedEvent extends NestedEvent
{
    const NAME = 'swag_product_discount.basic.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var SwagProductDiscountBasicCollection
     */
    protected $swagProductDiscounts;

    public function __construct(SwagProductDiscountBasicCollection $swagProductDiscounts, TranslationContext $context)
    {
        $this->context = $context;
        $this->swagProductDiscounts = $swagProductDiscounts;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getSwagProductDiscounts(): SwagProductDiscountBasicCollection
    {
        return $this->swagProductDiscounts;
    }

}