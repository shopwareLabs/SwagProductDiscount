<?php

namespace SwagProductDiscount\Repository;

use Shopware\Api\Search\AggregationResult;
use Shopware\Api\Search\UuidSearchResult;
use Shopware\Api\Search\Criteria;
use Shopware\Api\Search\EntityAggregatorInterface;
use Shopware\Api\Search\EntitySearcherInterface;
use Shopware\Api\Read\EntityReaderInterface;
use Shopware\Api\RepositoryInterface;
use Shopware\Api\Write\EntityWriterInterface;
use Shopware\Api\Write\WriteContext;
use Shopware\Api\Write\GenericWrittenEvent;
use Shopware\Context\Struct\TranslationContext;
use SwagProductDiscount\Event\SwagProductDiscount\SwagProductDiscountSearchResultLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscount\SwagProductDiscountBasicLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscount\SwagProductDiscountAggregationResultLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscount\SwagProductDiscountUuidSearchResultLoadedEvent;
use SwagProductDiscount\Struct\SwagProductDiscountSearchResult;
use SwagProductDiscount\Definition\SwagProductDiscountDefinition;
use SwagProductDiscount\Collection\SwagProductDiscountBasicCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use SwagProductDiscount\Collection\SwagProductDiscountDetailCollection;
use SwagProductDiscount\Event\SwagProductDiscount\SwagProductDiscountDetailLoadedEvent;

class SwagProductDiscountRepository implements RepositoryInterface
{
    /**
     * @var EntityReaderInterface
     */
    private $reader;

    /**
     * @var EntityWriterInterface
     */
    private $writer;

    /**
     * @var EntitySearcherInterface
     */
    private $searcher;

    /**
     * @var EntityAggregatorInterface
     */
    private $aggregator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        EntityReaderInterface $reader,
        EntityWriterInterface $writer,
        EntitySearcherInterface $searcher,
        EntityAggregatorInterface $aggregator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->searcher = $searcher;
        $this->aggregator = $aggregator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function search(Criteria $criteria, TranslationContext $context): SwagProductDiscountSearchResult
    {
        $uuids = $this->searchUuids($criteria, $context);

        $entities = $this->readBasic($uuids->getUuids(), $context);

        $aggregations = null;
        if ($criteria->getAggregations()) {
            $aggregations = $this->aggregate($criteria, $context);
        }

        $result = SwagProductDiscountSearchResult::createFromResults($uuids, $entities, $aggregations);

        $event = new SwagProductDiscountSearchResultLoadedEvent($result);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $result;
    }

    public function aggregate(Criteria $criteria, TranslationContext $context): AggregationResult
    {
        $result = $this->aggregator->aggregate(SwagProductDiscountDefinition::class, $criteria, $context);

        $event = new SwagProductDiscountAggregationResultLoadedEvent($result);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $result;
    }

    public function searchUuids(Criteria $criteria, TranslationContext $context): UuidSearchResult
    {
        $result = $this->searcher->search(SwagProductDiscountDefinition::class, $criteria, $context);

        $event = new SwagProductDiscountUuidSearchResultLoadedEvent($result);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $result;
    }

    public function readBasic(array $uuids, TranslationContext $context): SwagProductDiscountBasicCollection
    {
        /** @var SwagProductDiscountBasicCollection $entities */
        $entities = $this->reader->readBasic(SwagProductDiscountDefinition::class, $uuids, $context);

        $event = new SwagProductDiscountBasicLoadedEvent($entities, $context);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $entities;
    }

    public function readDetail(array $uuids, TranslationContext $context): SwagProductDiscountDetailCollection
    {

        /** @var SwagProductDiscountDetailCollection $entities */
        $entities = $this->reader->readDetail(SwagProductDiscountDefinition::class, $uuids, $context);

        $event = new SwagProductDiscountDetailLoadedEvent($entities, $context);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $entities;                
                
    }

    public function update(array $data, TranslationContext $context): GenericWrittenEvent
    {
        $affected = $this->writer->update(SwagProductDiscountDefinition::class, $data, WriteContext::createFromTranslationContext($context));
        $event = GenericWrittenEvent::createFromWriterResult($affected, $context, []);
        $this->eventDispatcher->dispatch(GenericWrittenEvent::NAME, $event);

        return $event;
    }

    public function upsert(array $data, TranslationContext $context): GenericWrittenEvent
    {
        $affected = $this->writer->upsert(SwagProductDiscountDefinition::class, $data, WriteContext::createFromTranslationContext($context));
        $event = GenericWrittenEvent::createFromWriterResult($affected, $context, []);
        $this->eventDispatcher->dispatch(GenericWrittenEvent::NAME, $event);

        return $event;
    }

    public function create(array $data, TranslationContext $context): GenericWrittenEvent
    {
        $affected = $this->writer->insert(SwagProductDiscountDefinition::class, $data, WriteContext::createFromTranslationContext($context));
        $event = GenericWrittenEvent::createFromWriterResult($affected, $context, []);
        $this->eventDispatcher->dispatch(GenericWrittenEvent::NAME, $event);

        return $event;
    }
}