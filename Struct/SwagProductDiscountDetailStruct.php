<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use Shopware\Product\Struct\ProductBasicStruct;
use SwagProductDiscount\Collection\SwagProductDiscountTranslationBasicCollection;

class SwagProductDiscountDetailStruct extends SwagProductDiscountBasicStruct
{

    /**
     * @var ProductBasicStruct
     */
    protected $product;

    /**
     * @var SwagProductDiscountTranslationBasicCollection
     */
    protected $translations;

    public function __construct()
    {

        $this->translations = new SwagProductDiscountTranslationBasicCollection();

    }


    public function getProduct(): ProductBasicStruct
    {
        return $this->product;
    }

    public function setProduct(ProductBasicStruct $product): void
    {
        $this->product = $product;
    }


    public function getTranslations(): SwagProductDiscountTranslationBasicCollection
    {
        return $this->translations;
    }

    public function setTranslations(SwagProductDiscountTranslationBasicCollection $translations): void
    {
        $this->translations = $translations;
    }

}