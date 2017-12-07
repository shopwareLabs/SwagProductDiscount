<?php declare(strict_types=1);

namespace SwagProductDiscount\Collection;

use SwagProductDiscount\Struct\SwagProductDiscountDetailStruct;
use Shopware\Product\Collection\ProductBasicCollection;

class SwagProductDiscountDetailCollection extends SwagProductDiscountBasicCollection
{
    /**
     * @var SwagProductDiscountDetailStruct[]
     */
    protected $elements = [];

    protected function getExpectedClass(): string
    {
        return SwagProductDiscountDetailStruct::class;
    }


    public function getProducts(): ProductBasicCollection
    {
        return new ProductBasicCollection(
            $this->fmap(function(SwagProductDiscountDetailStruct $swagProductDiscount) {
                return $swagProductDiscount->getProduct();
            })
        );
    }

    public function getTranslationUuids(): array
    {
        $uuids = [];
        foreach ($this->elements as $element) {
            foreach ($element->getTranslations()->getUuids() as $uuid) {
                $uuids[] = $uuid;
            }
        }

        return $uuids;
    }

    public function getTranslations(): SwagProductDiscountTranslationBasicCollection
    {
        $collection = new SwagProductDiscountTranslationBasicCollection();
        foreach ($this->elements as $element) {
            $collection->fill($element->getTranslations()->getElements());
        }
        return $collection;
    }
}