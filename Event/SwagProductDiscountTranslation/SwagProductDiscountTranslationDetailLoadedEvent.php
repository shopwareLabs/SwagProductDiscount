<?php

namespace SwagProductDiscount\Event\SwagProductDiscountTranslation;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use SwagProductDiscount\Collection\SwagProductDiscountTranslationDetailCollection;
use SwagProductDiscount\Event\SwagProductDiscount\SwagProductDiscountBasicLoadedEvent;
use Shopware\Shop\Event\Shop\ShopBasicLoadedEvent;

class SwagProductDiscountTranslationDetailLoadedEvent extends NestedEvent
{
    const NAME = 'swag_product_discount_translation.detail.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var SwagProductDiscountTranslationDetailCollection
     */
    protected $swagProductDiscountTranslations;

    public function __construct(SwagProductDiscountTranslationDetailCollection $swagProductDiscountTranslations, TranslationContext $context)
    {
        $this->context = $context;
        $this->swagProductDiscountTranslations = $swagProductDiscountTranslations;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getSwagProductDiscountTranslations(): SwagProductDiscountTranslationDetailCollection
    {
        return $this->swagProductDiscountTranslations;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->swagProductDiscountTranslations->getSwagProductDiscounts()->count() > 0) {
            $events[] = new SwagProductDiscountBasicLoadedEvent($this->swagProductDiscountTranslations->getSwagProductDiscounts(), $this->context);
        }
        if ($this->swagProductDiscountTranslations->getLanguages()->count() > 0) {
            $events[] = new ShopBasicLoadedEvent($this->swagProductDiscountTranslations->getLanguages(), $this->context);
        }
        return new NestedEventCollection($events);
    }            
            
}