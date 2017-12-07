<?php

namespace SwagProductDiscount\Event\SwagProductDiscount;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use SwagProductDiscount\Struct\SwagProductDiscountSearchResult;

class SwagProductDiscountSearchResultLoadedEvent extends NestedEvent
{
    const NAME = 'swag_product_discount.search.result.loaded';

    /**
     * @var SwagProductDiscountSearchResult
     */
    protected $result;

    public function __construct(SwagProductDiscountSearchResult $result)
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