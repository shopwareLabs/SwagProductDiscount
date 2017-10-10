<?php declare(strict_types=1);

namespace SwagProductDiscount;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Struct\Attribute;
use Shopware\Administration\Extension\ProductExtension as CoreProductExtension;
use Shopware\Product\Struct\ProductBasicStruct;
use Shopware\Search\QueryBuilder;
use Shopware\Search\QuerySelection;

class ProductExtension extends CoreProductExtension
{
    public function joinDependencies(
        QuerySelection $selection,
        QueryBuilder $query,
        TranslationContext $context
    ): void {
        if (!($fields = $selection->filter('attributes.discount'))) {
            return;
        }

        $query->leftJoin(
            $selection->getRootEscaped(),
            'swag_product_discount',
            $fields->getRootEscaped(),
            sprintf('%s.main_detail_uuid = %s.product_detail_uuid', $selection->getRootEscaped(), $fields->getRootEscaped())
        );
    }

    public function getBasicFields(): array
    {
        return [
            'attributes' => [
                'discount' => [
                    'percentage' => 'discount_percentage',
                ],
            ],
        ];
    }

    public function hydrate(
        ProductBasicStruct $product,
        array $data,
        QuerySelection $selection,
        TranslationContext $translation
    ): void {
        $discount = $selection->filter('attributes.discount');

        $field = $discount->getField('percentage');

        if (!array_key_exists($field, $data)) {
            return;
        }

        $product->addAttribute(
            'discount',
            new Attribute([
                'percentage' => (float) $data[$discount->getField('percentage')],
            ])
        );
    }
}
