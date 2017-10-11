<?php declare(strict_types=1);

namespace SwagProductDiscount\Cart;

use Doctrine\DBAL\Connection;
use Shopware\Cart\Cart\CartContainer;
use Shopware\Cart\Cart\CollectorInterface;
use Shopware\Cart\Product\ProductFetchDefinition;
use Shopware\Context\Struct\ShopContext;
use Shopware\Framework\Struct\Collection;
use Shopware\Framework\Struct\StructCollection;
use Shopware\Search\Criteria;
use Shopware\Search\Query\TermsQuery;
use SwagProductDiscount\Repository\SwagProductDiscountRepository;

class DiscountCollector implements CollectorInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SwagProductDiscountRepository
     */
    private $repository;

    public function __construct(Connection $connection, SwagProductDiscountRepository $repository)
    {
        $this->connection = $connection;
        $this->repository = $repository;
    }

    public function prepare(
        StructCollection $fetchDefinition,
        CartContainer $cartContainer,
        ShopContext $context
    ): void {
    }

    public function fetch(
        StructCollection $dataCollection,
        StructCollection $fetchCollection,
        ShopContext $context
    ): void {
        $definitions = $fetchCollection->filterInstance(ProductFetchDefinition::class);
        if ($definitions->count() === 0) {
            return;
        }

        $numbers = [];
        /** @var Collection $definitions */
        /** @var ProductFetchDefinition $definition */
        foreach ($definitions as $key => $definition) {
            foreach ($definition->getNumbers() as $number) {
                if (!in_array($number, $numbers)) {
                    $numbers[] = $number;
                }
            }
        }

        $criteria = new Criteria();
        $criteria->addFilter(new TermsQuery('swag_product_discount.productDetailUuid', $numbers));
        $discounts = $this->repository->search($criteria, $context->getTranslationContext());

        $dataCollection->add($discounts, 'product_discounts');
    }
}
