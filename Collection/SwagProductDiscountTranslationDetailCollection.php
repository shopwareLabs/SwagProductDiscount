<?php declare(strict_types=1);

namespace SwagProductDiscount\Collection;

use SwagProductDiscount\Struct\SwagProductDiscountTranslationDetailStruct;
use Shopware\Shop\Collection\ShopBasicCollection;

class SwagProductDiscountTranslationDetailCollection extends SwagProductDiscountTranslationBasicCollection
{
    /**
     * @var SwagProductDiscountTranslationDetailStruct[]
     */
    protected $elements = [];

    protected function getExpectedClass(): string
    {
        return SwagProductDiscountTranslationDetailStruct::class;
    }


    public function getSwagProductDiscounts(): SwagProductDiscountBasicCollection
    {
        return new SwagProductDiscountBasicCollection(
            $this->fmap(function(SwagProductDiscountTranslationDetailStruct $swagProductDiscountTranslation) {
                return $swagProductDiscountTranslation->getSwagProductDiscount();
            })
        );
    }

    public function getLanguages(): ShopBasicCollection
    {
        return new ShopBasicCollection(
            $this->fmap(function(SwagProductDiscountTranslationDetailStruct $swagProductDiscountTranslation) {
                return $swagProductDiscountTranslation->getLanguage();
            })
        );
    }
}