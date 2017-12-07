<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use Shopware\Api\Entity\Entity;



class SwagProductDiscountBasicStruct extends Entity
{

    /**
     * @var string
     */
    protected $productUuid;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $discountPercentage;


    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function setProductUuid(string $productUuid): void
    {
        $this->productUuid = $productUuid;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getDiscountPercentage(): float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(float $discountPercentage): void
    {
        $this->discountPercentage = $discountPercentage;
    }

}