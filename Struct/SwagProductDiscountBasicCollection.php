<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use Shopware\Framework\Struct\Collection;

class SwagProductDiscountBasicCollection extends Collection
{
    /**
     * @var SwagProductDiscountBasicStruct[]
     */
    protected $elements = [];

    public function add(SwagProductDiscountBasicStruct $swagProductDiscount): void
    {
        $key = $this->getKey($swagProductDiscount);
        $this->elements[$key] = $swagProductDiscount;
    }

    public function remove(string $uuid): void
    {
        parent::doRemoveByKey($uuid);
    }

    public function removeElement(SwagProductDiscountBasicStruct $swagProductDiscount): void
    {
        parent::doRemoveByKey($this->getKey($swagProductDiscount));
    }

    public function exists(SwagProductDiscountBasicStruct $swagProductDiscount): bool
    {
        return parent::has($this->getKey($swagProductDiscount));
    }

    public function getList(array $uuids): SwagProductDiscountBasicCollection
    {
        return new self(array_intersect_key($this->elements, array_flip($uuids)));
    }

    public function get(string $uuid): ? SwagProductDiscountBasicStruct
    {
        if ($this->has($uuid)) {
            return $this->elements[$uuid];
        }

        return null;
    }

    public function getUuids(): array
    {
        return $this->fmap(function (SwagProductDiscountBasicStruct $swagProductDiscount) {
            return $swagProductDiscount->getUuid();
        });
    }

    public function merge(SwagProductDiscountBasicCollection $collection)
    {
        /** @var SwagProductDiscountBasicStruct $swagProductDiscount */
        foreach ($collection as $swagProductDiscount) {
            if ($this->has($this->getKey($swagProductDiscount))) {
                continue;
            }
            $this->add($swagProductDiscount);
        }
    }

    public function getProductDetailUuids(): array
    {
        return $this->fmap(function (SwagProductDiscountBasicStruct $swagProductDiscount) {
            return $swagProductDiscount->getProductDetailUuid();
        });
    }

    public function filterByProductDetailUuid(string $uuid): SwagProductDiscountBasicCollection
    {
        return $this->filter(function (SwagProductDiscountBasicStruct $swagProductDiscount) use ($uuid) {
            return $swagProductDiscount->getProductDetailUuid() === $uuid;
        });
    }

    protected function getKey(SwagProductDiscountBasicStruct $element): string
    {
        return $element->getUuid();
    }
}
