<?php declare(strict_types=1);

namespace SwagProductDiscount\Test\Repository;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Search\Criteria;
use Shopware\Search\UuidSearchResult;
use SwagProductDiscount\Repository\SwagProductDiscountRepository;
use SwagProductDiscount\Searcher\SwagProductDiscountSearchResult;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SwagProductDiscountRepositoryTest extends KernelTestCase
{
    /**
     * @var SwagProductDiscountRepository
     */
    private $repository;

    public function setUp()
    {
        self::bootKernel();
        $this->repository = self::$kernel->getContainer()->get('shopware.swag_product_discount.repository');
    }

    public function testSearchUuidsReturnsUuidSearchResult()
    {
        $context = new TranslationContext('SWAG-SHOP-UUID-1', true, null);
        $criteria = new Criteria();
        $criteria->setLimit(1);
        $result = $this->repository->searchUuids($criteria, $context);

        $this->assertInstanceOf(UuidSearchResult::class, $result);
    }

    public function testSearchReturnsSearchResult()
    {
        $context = new TranslationContext('SWAG-SHOP-UUID-1', true, null);
        $criteria = new Criteria();
        $criteria->setLimit(1);

        $result = $this->repository->search($criteria, $context);
        $this->assertInstanceOf(SwagProductDiscountSearchResult::class, $result);
    }
}
