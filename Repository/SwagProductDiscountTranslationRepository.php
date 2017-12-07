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
use SwagProductDiscount\Event\SwagProductDiscountTranslation\SwagProductDiscountTranslationSearchResultLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountTranslation\SwagProductDiscountTranslationBasicLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountTranslation\SwagProductDiscountTranslationAggregationResultLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountTranslation\SwagProductDiscountTranslationUuidSearchResultLoadedEvent;
use SwagProductDiscount\Struct\SwagProductDiscountTranslationSearchResult;
use SwagProductDiscount\Definition\SwagProductDiscountTranslationDefinition;
use SwagProductDiscount\Collection\SwagProductDiscountTranslationBasicCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use SwagProductDiscount\Collection\SwagProductDiscountTranslationDetailCollection;
use SwagProductDiscount\Event\SwagProductDiscountTranslation\SwagProductDiscountTranslationDetailLoadedEvent;

class SwagProductDiscountTranslationRepository implements RepositoryInterface
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

    public function search(Criteria $criteria, TranslationContext $context): SwagProductDiscountTranslationSearchResult
    {
        $uuids = $this->searchUuids($criteria, $context);

        $entities = $this->readBasic($uuids->getUuids(), $context);

        $aggregations = null;
        if ($criteria->getAggregations()) {
            $aggregations = $this->aggregate($criteria, $context);
        }

        $result = SwagProductDiscountTranslationSearchResult::createFromResults($uuids, $entities, $aggregations);

        $event = new SwagProductDiscountTranslationSearchResultLoadedEvent($result);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $result;
    }

    public function aggregate(Criteria $criteria, TranslationContext $context): AggregationResult
    {
        $result = $this->aggregator->aggregate(SwagProductDiscountTranslationDefinition::class, $criteria, $context);

        $event = new SwagProductDiscountTranslationAggregationResultLoadedEvent($result);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $result;
    }

    public function searchUuids(Criteria $criteria, TranslationContext $context): UuidSearchResult
    {
        $result = $this->searcher->search(SwagProductDiscountTranslationDefinition::class, $criteria, $context);

        $event = new SwagProductDiscountTranslationUuidSearchResultLoadedEvent($result);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $result;
    }

    public function readBasic(array $uuids, TranslationContext $context): SwagProductDiscountTranslationBasicCollection
    {
        /** @var SwagProductDiscountTranslationBasicCollection $entities */
        $entities = $this->reader->readBasic(SwagProductDiscountTranslationDefinition::class, $uuids, $context);

        $event = new SwagProductDiscountTranslationBasicLoadedEvent($entities, $context);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $entities;
    }

    public function readDetail(array $uuids, TranslationContext $context): SwagProductDiscountTranslationDetailCollection
    {

        /** @var SwagProductDiscountTranslationDetailCollection $entities */
        $entities = $this->reader->readDetail(SwagProductDiscountTranslationDefinition::class, $uuids, $context);

        $event = new SwagProductDiscountTranslationDetailLoadedEvent($entities, $context);
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $entities;                
                
    }

    public function update(array $data, TranslationContext $context): GenericWrittenEvent
    {
        $affected = $this->writer->update(SwagProductDiscountTranslationDefinition::class, $data, WriteContext::createFromTranslationContext($context));
        $event = GenericWrittenEvent::createFromWriterResult($affected, $context, []);
        $this->eventDispatcher->dispatch(GenericWrittenEvent::NAME, $event);

        return $event;
    }

    public function upsert(array $data, TranslationContext $context): GenericWrittenEvent
    {
        $affected = $this->writer->upsert(SwagProductDiscountTranslationDefinition::class, $data, WriteContext::createFromTranslationContext($context));
        $event = GenericWrittenEvent::createFromWriterResult($affected, $context, []);
        $this->eventDispatcher->dispatch(GenericWrittenEvent::NAME, $event);

        return $event;
    }

    public function create(array $data, TranslationContext $context): GenericWrittenEvent
    {
        $affected = $this->writer->insert(SwagProductDiscountTranslationDefinition::class, $data, WriteContext::createFromTranslationContext($context));
        $event = GenericWrittenEvent::createFromWriterResult($affected, $context, []);
        $this->eventDispatcher->dispatch(GenericWrittenEvent::NAME, $event);

        return $event;
    }
}