<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;
use Shopware\Shop\Struct\ShopBasicStruct;

class SwagProductDiscountTranslationDetailStruct extends SwagProductDiscountTranslationBasicStruct
{

    /**
     * @var SwagProductDiscountBasicStruct
     */
    protected $swagProductDiscount;

    /**
     * @var ShopBasicStruct
     */
    protected $language;



    public function getSwagProductDiscount(): SwagProductDiscountBasicStruct
    {
        return $this->swagProductDiscount;
    }

    public function setSwagProductDiscount(SwagProductDiscountBasicStruct $swagProductDiscount): void
    {
        $this->swagProductDiscount = $swagProductDiscount;
    }


    public function getLanguage(): ShopBasicStruct
    {
        return $this->language;
    }

    public function setLanguage(ShopBasicStruct $language): void
    {
        $this->language = $language;
    }

}