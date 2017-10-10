<?php declare(strict_types=1);

namespace SwagProductDiscount\Extension;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Factory\ExtensionInterface;
use Shopware\Search\QueryBuilder;
use Shopware\Search\QuerySelection;
use SwagProductDiscount\Event\SwagProductDiscountBasicLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountDetailLoadedEvent;
use SwagProductDiscount\Event\SwagProductDiscountWrittenEvent;
use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class SwagProductDiscountExtension implements ExtensionInterface, EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SwagProductDiscountBasicLoadedEvent::NAME => 'swagProductDiscountBasicLoaded',
            SwagProductDiscountDetailLoadedEvent::NAME => 'swagProductDiscountDetailLoaded',
            SwagProductDiscountWrittenEvent::NAME => 'swagProductDiscountWritten',
        ];
    }

    public function joinDependencies(
        QuerySelection $selection,
        QueryBuilder $query,
        TranslationContext $context
    ): void {
    }

    public function getDetailFields(): array
    {
        return [];
    }

    public function getBasicFields(): array
    {
        return [];
    }

    public function hydrate(
        SwagProductDiscountBasicStruct $swagProductDiscount,
        array $data,
        QuerySelection $selection,
        TranslationContext $translation
    ): void {
    }

    public function swagProductDiscountBasicLoaded(SwagProductDiscountBasicLoadedEvent $event): void
    {
    }

    public function swagProductDiscountDetailLoaded(SwagProductDiscountDetailLoadedEvent $event): void
    {
    }

    public function swagProductDiscountWritten(SwagProductDiscountWrittenEvent $event): void
    {
    }
}
