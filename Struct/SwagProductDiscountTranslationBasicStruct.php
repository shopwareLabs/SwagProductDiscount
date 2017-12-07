<?php declare(strict_types=1);

namespace SwagProductDiscount\Struct;

use Shopware\Api\Entity\Entity;



class SwagProductDiscountTranslationBasicStruct extends Entity
{

    /**
     * @var string
     */
    protected $swagProductDiscountUuid;

    /**
     * @var string
     */
    protected $languageUuid;

    /**
     * @var string
     */
    protected $name;


    public function getSwagProductDiscountUuid(): string
    {
        return $this->swagProductDiscountUuid;
    }

    public function setSwagProductDiscountUuid(string $swagProductDiscountUuid): void
    {
        $this->swagProductDiscountUuid = $swagProductDiscountUuid;
    }


    public function getLanguageUuid(): string
    {
        return $this->languageUuid;
    }

    public function setLanguageUuid(string $languageUuid): void
    {
        $this->languageUuid = $languageUuid;
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