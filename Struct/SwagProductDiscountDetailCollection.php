<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use Shopware\ProductDetail\Struct\ProductDetailBasicCollection;

class SwagProductDiscountDetailCollection extends SwagProductDiscountBasicCollection
{
    /**
     * @var SwagProductDiscountDetailStruct[]
     */
    protected $elements = [];

    public function getProduct_details(): ProductDetailBasicCollection
    {
        return new ProductDetailBasicCollection(
            $this->fmap(function (SwagProductDiscountDetailStruct $swagProductDiscount) {
                return $swagProductDiscount->getProduct_detail();
            })
        );
    }
}
