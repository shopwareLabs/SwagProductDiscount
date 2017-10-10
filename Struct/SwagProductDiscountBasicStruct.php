<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use Shopware\Framework\Struct\Struct;

class SwagProductDiscountBasicStruct extends Struct
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $productDetailUuid;

    /**
     * @var float
     */
    protected $discountPercentage;

    /**
     * @var string
     */
    protected $name;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getProductDetailUuid(): string
    {
        return $this->productDetailUuid;
    }

    public function setProductDetailUuid(string $productDetailUuid): void
    {
        $this->productDetailUuid = $productDetailUuid;
    }

    public function getDiscountPercentage(): float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(float $discountPercentage): void
    {
        $this->discountPercentage = $discountPercentage;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
