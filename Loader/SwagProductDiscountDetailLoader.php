<?php declare(strict_types=1);

namespace SwagProductDiscount\Loader;

use Doctrine\DBAL\Connection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Struct\SortArrayByKeysTrait;
use Shopware\ProductDetail\Loader\ProductDetailBasicLoader;
use SwagProductDiscount\Factory\SwagProductDiscountDetailFactory;
use SwagProductDiscount\Struct\SwagProductDiscountDetailCollection;
use SwagProductDiscount\Struct\SwagProductDiscountDetailStruct;

class SwagProductDiscountDetailLoader
{
    use SortArrayByKeysTrait;

    /**
     * @var SwagProductDiscountDetailFactory
     */
    private $factory;

    /**
     * @var ProductDetailBasicLoader
     */
    private $productDetailBasicLoader;

    public function __construct(
        SwagProductDiscountDetailFactory $factory,
        ProductDetailBasicLoader $productDetailBasicLoader
    ) {
        $this->factory = $factory;
        $this->productDetailBasicLoader = $productDetailBasicLoader;
    }

    public function load(array $uuids, TranslationContext $context): SwagProductDiscountDetailCollection
    {
        if (empty($uuids)) {
            return new SwagProductDiscountDetailCollection();
        }

        $swagProductDiscountsCollection = $this->read($uuids, $context);

        $product_details = $this->productDetailBasicLoader->load($swagProductDiscountsCollection->getProduct_detailUuids(), $context);

        /** @var SwagProductDiscountDetailStruct $swagProductDiscount */
        foreach ($swagProductDiscountsCollection as $swagProductDiscount) {
            if ($swagProductDiscount->getProduct_detailUuid()) {
                $swagProductDiscount->setProduct_detail($product_details->get($swagProductDiscount->getProduct_detailUuid()));
            }
        }

        return $swagProductDiscountsCollection;
    }

    private function read(array $uuids, TranslationContext $context): SwagProductDiscountDetailCollection
    {
        $query = $this->factory->createQuery($context);

        $query->andWhere('swag_product_discount.uuid IN (:ids)');
        $query->setParameter(':ids', $uuids, Connection::PARAM_STR_ARRAY);

        $rows = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
        $structs = [];
        foreach ($rows as $row) {
            $struct = $this->factory->hydrate($row, new SwagProductDiscountDetailStruct(), $query->getSelection(), $context);
            $structs[$struct->getUuid()] = $struct;
        }

        return new SwagProductDiscountDetailCollection(
            $this->sortIndexedArrayByKeys($uuids, $structs)
        );
    }
}
