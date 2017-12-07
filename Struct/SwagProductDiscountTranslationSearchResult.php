<?php

namespace SwagProductDiscount\Struct;

use Shopware\Api\Search\SearchResultInterface;
use Shopware\Api\Search\SearchResultTrait;
use SwagProductDiscount\Collection\SwagProductDiscountTranslationBasicCollection;

class SwagProductDiscountTranslationSearchResult extends SwagProductDiscountTranslationBasicCollection implements SearchResultInterface
{
    use SearchResultTrait;
}
