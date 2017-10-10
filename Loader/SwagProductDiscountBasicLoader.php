<?php declare(strict_types=1);

namespace SwagProductDiscount\Loader;

use Doctrine\DBAL\Connection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Struct\SortArrayByKeysTrait;
use SwagProductDiscount\Factory\SwagProductDiscountBasicFactory;
use SwagProductDiscount\Struct\SwagProductDiscountBasicCollection;
use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;

class SwagProductDiscountBasicLoader
{
    use SortArrayByKeysTrait;

    /**
     * @var SwagProductDiscountBasicFactory
     */
    private $factory;

    public function __construct(
        SwagProductDiscountBasicFactory $factory
    ) {
        $this->factory = $factory;
    }

    public function load(array $uuids, TranslationContext $context): SwagProductDiscountBasicCollection
    {
        if (empty($uuids)) {
            return new SwagProductDiscountBasicCollection();
        }

        $swagProductDiscountsCollection = $this->read($uuids, $context);

        return $swagProductDiscountsCollection;
    }

    private function read(array $uuids, TranslationContext $context): SwagProductDiscountBasicCollection
    {
        $query = $this->factory->createQuery($context);

        $query->andWhere('swag_product_discount.uuid IN (:ids)');
        $query->setParameter(':ids', $uuids, Connection::PARAM_STR_ARRAY);

        $rows = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
        $structs = [];
        foreach ($rows as $row) {
            $struct = $this->factory->hydrate($row, new SwagProductDiscountBasicStruct(), $query->getSelection(), $context);
            $structs[$struct->getUuid()] = $struct;
        }

        return new SwagProductDiscountBasicCollection(
            $this->sortIndexedArrayByKeys($uuids, $structs)
        );
    }
}
