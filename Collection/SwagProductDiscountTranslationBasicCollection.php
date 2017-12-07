<?php declare(strict_types=1);

namespace SwagProductDiscount\Collection;

use Shopware\Api\Entity\EntityCollection;
use SwagProductDiscount\Struct\SwagProductDiscountTranslationBasicStruct;


class SwagProductDiscountTranslationBasicCollection extends EntityCollection
{
    /**
     * @var SwagProductDiscountTranslationBasicStruct[]
     */
    protected $elements = [];

    public function get(string $uuid): ? SwagProductDiscountTranslationBasicStruct
    {
        return parent::get($uuid);
    }

    public function current(): SwagProductDiscountTranslationBasicStruct
    {
        return parent::current();
    }


    public function getSwagProductDiscountUuids(): array
    {
        return $this->fmap(function(SwagProductDiscountTranslationBasicStruct $swagProductDiscountTranslation) {
            return $swagProductDiscountTranslation->getSwagProductDiscountUuid();
        });
    }

    public function filterBySwagProductDiscountUuid(string $uuid): SwagProductDiscountTranslationBasicCollection
    {
        return $this->filter(function(SwagProductDiscountTranslationBasicStruct $swagProductDiscountTranslation) use ($uuid) {
            return $swagProductDiscountTranslation->getSwagProductDiscountUuid() === $uuid;
        });
    }

    public function getLanguageUuids(): array
    {
        return $this->fmap(function(SwagProductDiscountTranslationBasicStruct $swagProductDiscountTranslation) {
            return $swagProductDiscountTranslation->getLanguageUuid();
        });
    }

    public function filterByLanguageUuid(string $uuid): SwagProductDiscountTranslationBasicCollection
    {
        return $this->filter(function(SwagProductDiscountTranslationBasicStruct $swagProductDiscountTranslation) use ($uuid) {
            return $swagProductDiscountTranslation->getLanguageUuid() === $uuid;
        });
    }

    protected function getExpectedClass(): string
    {
        return SwagProductDiscountTranslationBasicStruct::class;
    }
}