<?php declare(strict_types=1);

namespace SwagProductDiscount\Searcher;

use Shopware\Search\SearchResultInterface;
use SwagProductDiscount\Struct\SwagProductDiscountBasicCollection;

class SwagProductDiscountSearchResult extends SwagProductDiscountBasicCollection implements SearchResultInterface
{
    /**
     * @var int
     */
    protected $total = 0;

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
