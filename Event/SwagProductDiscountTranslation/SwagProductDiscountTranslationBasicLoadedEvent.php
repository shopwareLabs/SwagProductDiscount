<?php

namespace SwagProductDiscount\Event\SwagProductDiscountTranslation;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use SwagProductDiscount\Collection\SwagProductDiscountTranslationBasicCollection;


class SwagProductDiscountTranslationBasicLoadedEvent extends NestedEvent
{
    const NAME = 'swag_product_discount_translation.basic.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var SwagProductDiscountTranslationBasicCollection
     */
    protected $swagProductDiscountTranslations;

    public function __construct(SwagProductDiscountTranslationBasicCollection $swagProductDiscountTranslations, TranslationContext $context)
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

    public function getSwagProductDiscountTranslations(): SwagProductDiscountTranslationBasicCollection
    {
        return $this->swagProductDiscountTranslations;
    }

}