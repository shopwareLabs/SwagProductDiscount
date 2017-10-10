<?php declare(strict_types=1);

namespace SwagProductDiscount\Event;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use SwagProductDiscount\Struct\SwagProductDiscountBasicCollection;

class SwagProductDiscountBasicLoadedEvent extends NestedEvent
{
    const NAME = 'swagProductDiscount.basic.loaded';

    /**
     * @var SwagProductDiscountBasicCollection
     */
    protected $swagProductDiscounts;

    /**
     * @var TranslationContext
     */
    protected $context;

    public function __construct(SwagProductDiscountBasicCollection $swagProductDiscounts, TranslationContext $context)
    {
        $this->swagProductDiscounts = $swagProductDiscounts;
        $this->context = $context;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSwagProductDiscounts(): SwagProductDiscountBasicCollection
    {
        return $this->swagProductDiscounts;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];

        return new NestedEventCollection($events);
    }
}
