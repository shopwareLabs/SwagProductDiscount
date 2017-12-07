<?php

namespace SwagProductDiscount\Struct;

use Shopware\Api\Search\SearchResultInterface;
use Shopware\Api\Search\SearchResultTrait;
use SwagProductDiscount\Collection\SwagProductDiscountBasicCollection;

class SwagProductDiscountSearchResult extends SwagProductDiscountBasicCollection implements SearchResultInterface
{
    use SearchResultTrait;
}
