<?php declare(strict_types=1);

namespace SwagProductDiscount\Factory;

use Doctrine\DBAL\Connection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Factory\ExtensionRegistryInterface;
use Shopware\ProductDetail\Factory\ProductDetailBasicFactory;
use Shopware\ProductDetail\Struct\ProductDetailBasicStruct;
use Shopware\Search\QueryBuilder;
use Shopware\Search\QuerySelection;
use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;
use SwagProductDiscount\Struct\SwagProductDiscountDetailStruct;

class SwagProductDiscountDetailFactory extends SwagProductDiscountBasicFactory
{
    /**
     * @var ProductDetailBasicFactory
     */
    protected $productDetailFactory;

    public function __construct(
        Connection $connection,
        ExtensionRegistryInterface $registry,
        ProductDetailBasicFactory $productDetailFactory
    ) {
        parent::__construct($connection, $registry);
        $this->productDetailFactory = $productDetailFactory;
    }

    public function getFields(): array
    {
        $fields = array_merge(parent::getFields(), $this->getExtensionFields());
        $fields['product_detail'] = $this->productDetailFactory->getFields();

        return $fields;
    }

    public function hydrate(
        array $data,
        SwagProductDiscountBasicStruct $swagProductDiscount,
        QuerySelection $selection,
        TranslationContext $context
    ): SwagProductDiscountBasicStruct {
        /** @var SwagProductDiscountDetailStruct $swagProductDiscount */
        $swagProductDiscount = parent::hydrate($data, $swagProductDiscount, $selection, $context);
        $productDetail = $selection->filter('product_detail');
        if ($productDetail && !empty($data[$productDetail->getField('uuid')])) {
            $swagProductDiscount->setProduct_detail(
                $this->productDetailFactory->hydrate($data, new ProductDetailBasicStruct(), $productDetail, $context)
            );
        }

        return $swagProductDiscount;
    }

    public function joinDependencies(QuerySelection $selection, QueryBuilder $query, TranslationContext $context): void
    {
        parent::joinDependencies($selection, $query, $context);

        $this->joinProduct_detail($selection, $query, $context);
    }

    public function getAllFields(): array
    {
        $fields = parent::getAllFields();
        $fields['product_detail'] = $this->productDetailFactory->getAllFields();

        return $fields;
    }

    protected function getExtensionFields(): array
    {
        $fields = parent::getExtensionFields();

        foreach ($this->getExtensions() as $extension) {
            $extensionFields = $extension->getDetailFields();
            foreach ($extensionFields as $key => $field) {
                $fields[$key] = $field;
            }
        }

        return $fields;
    }

    private function joinProduct_detail(
        QuerySelection $selection,
        QueryBuilder $query,
        TranslationContext $context
    ): void {
        if (!($productDetail = $selection->filter('product_detail'))) {
            return;
        }
        $query->leftJoin(
            $selection->getRootEscaped(),
            'product_detail',
            $productDetail->getRootEscaped(),
            sprintf('%s.uuid = %s.product_detail_uuid', $productDetail->getRootEscaped(), $selection->getRootEscaped())
        );
        $this->productDetailFactory->joinDependencies($productDetail, $query, $context);
    }
}
