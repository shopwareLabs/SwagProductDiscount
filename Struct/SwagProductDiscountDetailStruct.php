<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use Shopware\ProductDetail\Struct\ProductDetailBasicStruct;

class SwagProductDiscountDetailStruct extends SwagProductDiscountBasicStruct
{
    /**
     * @var ProductDetailBasicStruct
     */
    protected $product_detail;
    //constructor#

    public function getProduct_detail(): ProductDetailBasicStruct
    {
        return $this->product_detail;
    }

    public function setProduct_detail(ProductDetailBasicStruct $product_detail): void
    {
        $this->product_detail = $product_detail;
    }
}
