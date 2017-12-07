<?php declare(strict_types=1);

namespace SwagProductDiscount\Cart;

use Shopware\Cart\Cart\CartProcessorInterface;
use Shopware\Cart\Cart\Struct\CalculatedCart;
use Shopware\Cart\Cart\Struct\CartContainer;
use Shopware\Cart\LineItem\CalculatedLineItem;
use Shopware\Cart\LineItem\CalculatedLineItemCollection;
use Shopware\Cart\Price\PercentagePriceCalculator;
use Shopware\Cart\Price\PriceCalculator;
use Shopware\Cart\Price\Struct\PriceCollection;
use Shopware\Cart\Tax\PercentageTaxRuleBuilder;
use Shopware\CartBridge\Product\Struct\CalculatedProduct;
use Shopware\Context\Struct\ShopContext;
use Shopware\Framework\Struct\StructCollection;
use SwagProductDiscount\Collection\SwagProductDiscountBasicCollection;
use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;

class DiscountProcessor implements CartProcessorInterface
{
    /**
     * @var PercentagePriceCalculator
     */
    private $percentagePriceCalculator;

    /**
     * @var PriceCalculator
     */
    private $priceCalculator;

    /**
     * @var PercentageTaxRuleBuilder
     */
    private $percentageTaxRuleBuilder;

    /**
     * @param PercentagePriceCalculator $percentagePriceCalculator
     * @param PriceCalculator           $priceCalculator
     * @param PercentageTaxRuleBuilder  $percentageTaxRuleBuilder
     */
    public function __construct(
        PercentagePriceCalculator $percentagePriceCalculator,
        PriceCalculator $priceCalculator,
        PercentageTaxRuleBuilder $percentageTaxRuleBuilder
    ) {
        $this->percentagePriceCalculator = $percentagePriceCalculator;
        $this->priceCalculator = $priceCalculator;
        $this->percentageTaxRuleBuilder = $percentageTaxRuleBuilder;
    }

    public function process(
        CartContainer $cartContainer,
        CalculatedCart $calculatedCart,
        StructCollection $dataCollection,
        ShopContext $context
    ): void {
        /** @var CalculatedLineItemCollection $products */

        $products = $calculatedCart->getCalculatedLineItems()->filterInstance(
            CalculatedProduct::class
        );

        /** @var SwagProductDiscountBasicCollection $discounts */
        $discounts = $dataCollection->get('product_discounts');

        /** @var CalculatedProduct $product */
        foreach ($products as $product) {
            $discount = $discounts->filterByProductUuid($product->getIdentifier())->first();

            if (!$discount) {
                continue;
            }

            /** @var SwagProductDiscountBasicStruct $discount */
            $price = $this->percentagePriceCalculator->calculate(
                $discount->getDiscountPercentage() * -1,
                new PriceCollection([$product->getPrice()]),
                $context
            );

            $calculatedCart->getCalculatedLineItems()->add(
                new CalculatedLineItem(
                    'swag_discount_' . $product->getIdentifier(),
                    $price,
                    1,
                    'swag_discount',
                    $discount->getName()
                )
            );
        }
    }
}
