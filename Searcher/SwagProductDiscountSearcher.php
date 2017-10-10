<?php declare(strict_types=1);

namespace SwagProductDiscount\Searcher;

use Doctrine\DBAL\Connection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Search\Criteria;
use Shopware\Search\Parser\SqlParser;
use Shopware\Search\QueryBuilder;
use Shopware\Search\Searcher;
use Shopware\Search\SearchResultInterface;
use Shopware\Search\UuidSearchResult;
use SwagProductDiscount\Factory\SwagProductDiscountDetailFactory;
use SwagProductDiscount\Loader\SwagProductDiscountBasicLoader;

class SwagProductDiscountSearcher extends Searcher
{
    /**
     * @var SwagProductDiscountDetailFactory
     */
    private $factory;

    /**
     * @var SwagProductDiscountBasicLoader
     */
    private $loader;

    public function __construct(Connection $connection, SqlParser $parser, SwagProductDiscountDetailFactory $factory, SwagProductDiscountBasicLoader $loader)
    {
        parent::__construct($connection, $parser);
        $this->factory = $factory;
        $this->loader = $loader;
    }

    protected function createQuery(Criteria $criteria, TranslationContext $context): QueryBuilder
    {
        return $this->factory->createSearchQuery($criteria, $context);
    }

    protected function load(UuidSearchResult $uuidResult, TranslationContext $context): SearchResultInterface
    {
        $collection = $this->loader->load($uuidResult->getUuids(), $context);

        $result = new SwagProductDiscountSearchResult($collection->getElements());

        $result->setTotal($uuidResult->getTotal());

        return $result;
    }
}
