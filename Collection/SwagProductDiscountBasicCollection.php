<?php declare(strict_types=1);

namespace SwagProductDiscount\Collection;

use Shopware\Api\Entity\EntityCollection;
use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;


class SwagProductDiscountBasicCollection extends EntityCollection
{
    /**
     * @var SwagProductDiscountBasicStruct[]
     */
    protected $elements = [];

    public function get(string $uuid): ? SwagProductDiscountBasicStruct
    {
        return parent::get($uuid);
    }

    public function current(): SwagProductDiscountBasicStruct
    {
        return parent::current();
    }


    public function getProductUuids(): array
    {
        return $this->fmap(function(SwagProductDiscountBasicStruct $swagProductDiscount) {
            return $swagProductDiscount->getProductUuid();
        });
    }

    public function filterByProductUuid(string $uuid): SwagProductDiscountBasicCollection
    {
        return $this->filter(function(SwagProductDiscountBasicStruct $swagProductDiscount) use ($uuid) {
            return $swagProductDiscount->getProductUuid() === $uuid;
        });
    }

    protected function getExpectedClass(): string
    {
        return SwagProductDiscountBasicStruct::class;
    }
}