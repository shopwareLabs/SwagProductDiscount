<?php declare(strict_types=1);

namespace SwagProductDiscount\Repository;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Search\AggregationResult;
use Shopware\Search\Criteria;
use Shopware\Search\UuidSearchResult;
use SwagProductDiscount\Event\SwagProductDiscountBasicLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountDetailLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountWrittenEvent;
use SwagProductDiscount\Loader\SwagProductDiscountBasicLoader;
use SwagProductDiscount\Loader\SwagProductDiscountDetailLoader;
use SwagProductDiscount\Searcher\SwagProductDiscountSearcher;
use SwagProductDiscount\Searcher\SwagProductDiscountSearchResult;
use SwagProductDiscount\Struct\SwagProductDiscountBasicCollection;
use SwagProductDiscount\Struct\SwagProductDiscountDetailCollection;
use SwagProductDiscount\Writer\SwagProductDiscountWriter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SwagProductDiscountRepository
{
    /**
     * @var SwagProductDiscountDetailLoader
     */
    protected $detailLoader;

    /**
     * @var SwagProductDiscountBasicLoader
     */
    private $basicLoader;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var SwagProductDiscountSearcher
     */
    private $searcher;

    /**
     * @var SwagProductDiscountWriter
     */
    private $writer;

    public function __construct(
        SwagProductDiscountDetailLoader $detailLoader,
        SwagProductDiscountBasicLoader $basicLoader,
        EventDispatcherInterface $eventDispatcher,
        SwagProductDiscountSearcher $searcher,
        SwagProductDiscountWriter $writer
    ) {
        $this->detailLoader = $detailLoader;
        $this->basicLoader = $basicLoader;
        $this->eventDispatcher = $eventDispatcher;
        $this->searcher = $searcher;
        $this->writer = $writer;
    }

    public function readDetail(array $uuids, TranslationContext $context): SwagProductDiscountDetailCollection
    {
        if (empty($uuids)) {
            return new SwagProductDiscountDetailCollection();
        }
        $collection = $this->detailLoader->load($uuids, $context);

        $this->eventDispatcher->dispatch(
            SwagProductDiscountDetailLoadedEvent::NAME,
            new SwagProductDiscountDetailLoadedEvent($collection, $context)
        );

        return $collection;
    }

    public function read(array $uuids, TranslationContext $context): SwagProductDiscountBasicCollection
    {
        if (empty($uuids)) {
            return new SwagProductDiscountBasicCollection();
        }

        $collection = $this->basicLoader->load($uuids, $context);

        $this->eventDispatcher->dispatch(
            SwagProductDiscountBasicLoadedEvent::NAME,
            new SwagProductDiscountBasicLoadedEvent($collection, $context)
        );

        return $collection;
    }

    public function search(Criteria $criteria, TranslationContext $context): SwagProductDiscountSearchResult
    {
        /** @var SwagProductDiscountSearchResult $result */
        $result = $this->searcher->search($criteria, $context);

        $this->eventDispatcher->dispatch(
            SwagProductDiscountBasicLoadedEvent::NAME,
            new SwagProductDiscountBasicLoadedEvent($result, $context)
        );

        return $result;
    }

    public function searchUuids(Criteria $criteria, TranslationContext $context): UuidSearchResult
    {
        return $this->searcher->searchUuids($criteria, $context);
    }

    public function aggregate(Criteria $criteria, TranslationContext $context): AggregationResult
    {
        $result = $this->searcher->aggregate($criteria, $context);

        return $result;
    }

    public function update(array $data, TranslationContext $context): SwagProductDiscountWrittenEvent
    {
        $event = $this->writer->update($data, $context);

        $this->eventDispatcher->dispatch($event::NAME, $event);

        return $event;
    }

    public function upsert(array $data, TranslationContext $context): SwagProductDiscountWrittenEvent
    {
        $event = $this->writer->upsert($data, $context);

        $this->eventDispatcher->dispatch($event::NAME, $event);

        return $event;
    }

    public function create(array $data, TranslationContext $context): SwagProductDiscountWrittenEvent
    {
        $event = $this->writer->create($data, $context);

        $this->eventDispatcher->dispatch($event::NAME, $event);

        return $event;
    }
}
