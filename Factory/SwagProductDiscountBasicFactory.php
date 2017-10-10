<?php declare(strict_types=1);

namespace SwagProductDiscount\Factory;

use Doctrine\DBAL\Connection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Factory\ExtensionRegistryInterface;
use Shopware\Framework\Factory\Factory;
use Shopware\Search\QueryBuilder;
use Shopware\Search\QuerySelection;
use SwagProductDiscount\Extension\SwagProductDiscountExtension;
use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;

class SwagProductDiscountBasicFactory extends Factory
{
    const ROOT_NAME = 'swag_product_discount';
    const EXTENSION_NAMESPACE = 'swagProductDiscount';

    const FIELDS = [
       'uuid' => 'uuid',
       'productDetailUuid' => 'product_detail_uuid',
       'discountPercentage' => 'discount_percentage',
       'name' => 'translation.name',
    ];

    public function __construct(
        Connection $connection,
        ExtensionRegistryInterface $registry
    ) {
        parent::__construct($connection, $registry);
    }

    public function hydrate(
        array $data,
        SwagProductDiscountBasicStruct $swagProductDiscount,
        QuerySelection $selection,
        TranslationContext $context
    ): SwagProductDiscountBasicStruct {
        $swagProductDiscount->setUuid((string) $data[$selection->getField('uuid')]);
        $swagProductDiscount->setProductDetailUuid((string) $data[$selection->getField('productDetailUuid')]);
        $swagProductDiscount->setDiscountPercentage((float) $data[$selection->getField('discountPercentage')]);
        $swagProductDiscount->setName((string) $data[$selection->getField('name')]);

        /** @var $extension SwagProductDiscountExtension */
        foreach ($this->getExtensions() as $extension) {
            $extension->hydrate($swagProductDiscount, $data, $selection, $context);
        }

        return $swagProductDiscount;
    }

    public function getFields(): array
    {
        $fields = array_merge(self::FIELDS, parent::getFields());

        return $fields;
    }

    public function joinDependencies(QuerySelection $selection, QueryBuilder $query, TranslationContext $context): void
    {
        $this->joinTranslation($selection, $query, $context);

        $this->joinExtensionDependencies($selection, $query, $context);
    }

    public function getAllFields(): array
    {
        $fields = array_merge(self::FIELDS, $this->getExtensionFields());

        return $fields;
    }

    protected function getRootName(): string
    {
        return self::ROOT_NAME;
    }

    protected function getExtensionNamespace(): string
    {
        return self::EXTENSION_NAMESPACE;
    }

    private function joinTranslation(
        QuerySelection $selection,
        QueryBuilder $query,
        TranslationContext $context
    ): void {
        if (!($translation = $selection->filter('translation'))) {
            return;
        }
        $query->leftJoin(
            $selection->getRootEscaped(),
            'swag_product_discount_translation',
            $translation->getRootEscaped(),
            sprintf(
                '%s.swag_product_discount_uuid = %s.uuid AND %s.language_uuid = :languageUuid',
                $translation->getRootEscaped(),
                $selection->getRootEscaped(),
                $translation->getRootEscaped()
            )
        );
        $query->setParameter('languageUuid', $context->getShopUuid());
    }
}
