<?php

namespace SwagProductDiscount\Event\SwagProductDiscount;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use SwagProductDiscount\Collection\SwagProductDiscountDetailCollection;
use Shopware\Product\Event\Product\ProductBasicLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountTranslation\SwagProductDiscountTranslationBasicLoadedEvent;

class SwagProductDiscountDetailLoadedEvent extends NestedEvent
{
    const NAME = 'swag_product_discount.detail.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var SwagProductDiscountDetailCollection
     */
    protected $swagProductDiscounts;

    public function __construct(SwagProductDiscountDetailCollection $swagProductDiscounts, TranslationContext $context)
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

    public function getSwagProductDiscounts(): SwagProductDiscountDetailCollection
    {
        return $this->swagProductDiscounts;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->swagProductDiscounts->getProducts()->count() > 0) {
            $events[] = new ProductBasicLoadedEvent($this->swagProductDiscounts->getProducts(), $this->context);
        }
        if ($this->swagProductDiscounts->getTranslations()->count() > 0) {
            $events[] = new SwagProductDiscountTranslationBasicLoadedEvent($this->swagProductDiscounts->getTranslations(), $this->context);
        }
        return new NestedEventCollection($events);
    }            
            
}