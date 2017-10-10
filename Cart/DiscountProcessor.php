<?php declare(strict_types=1);

namespace SwagProductDiscount\Cart;

use Shopware\Cart\Cart\CartContainer;
use Shopware\Cart\Cart\CartProcessorInterface;
use Shopware\Cart\Cart\ProcessorCart;
use Shopware\Cart\LineItem\CalculatedLineItemCollection;
use Shopware\Cart\LineItem\Discount;
use Shopware\Cart\Price\PercentagePriceCalculator;
use Shopware\Cart\Price\PriceCalculator;
use Shopware\Cart\Price\PriceCollection;
use Shopware\Cart\Product\CalculatedProduct;
use Shopware\Cart\Tax\PercentageTaxRuleBuilder;
use Shopware\Context\Struct\ShopContext;
use Shopware\Framework\Struct\StructCollection;
use SwagProductDiscount\Struct\SwagProductDiscountBasicCollection;
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
        ProcessorCart $processorCart,
        StructCollection $dataCollection,
        ShopContext $context
    ): void {
        /** @var CalculatedLineItemCollection $products */
        $products = $processorCart->getCalculatedLineItems()->filterInstance(
            CalculatedProduct::class
        );

        /** @var SwagProductDiscountBasicCollection $discounts */
        $discounts = $dataCollection->get('product_discounts');

        /** @var CalculatedProduct $product */
        foreach ($products as $product) {
            $discount = $discounts->filterByProductDetailUuid($product->getIdentifier())->first();

            if (!$discount) {
                continue;
            }

            /** @var SwagProductDiscountBasicStruct $discount */
            $price = $this->percentagePriceCalculator->calculate(
                $discount->getDiscountPercentage() * -1,
                new PriceCollection([$product->getPrice()]),
                $context
            );

            $processorCart->getCalculatedLineItems()->add(
                new Discount(
                    'discount_' . $product->getIdentifier(),
                    $price,
                    $discount->getName()
                )
            );
        }
    }
}
