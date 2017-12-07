<?php

namespace SwagProductDiscount\Event\SwagProductDiscountTranslation;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use SwagProductDiscount\Struct\SwagProductDiscountTranslationSearchResult;

class SwagProductDiscountTranslationSearchResultLoadedEvent extends NestedEvent
{
    const NAME = 'swag_product_discount_translation.search.result.loaded';

    /**
     * @var SwagProductDiscountTranslationSearchResult
     */
    protected $result;

    public function __construct(SwagProductDiscountTranslationSearchResult $result)
    {
        $this->result = $result;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->result->getContext();
    }
}