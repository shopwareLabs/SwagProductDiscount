<?php declare(strict_types=1);

namespace SwagProductDiscount\Event;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use Shopware\ProductDetail\Event\ProductDetailBasicLoadedEvent;
use SwagProductDiscount\Struct\SwagProductDiscountDetailCollection;

class SwagProductDiscountDetailLoadedEvent extends NestedEvent
{
    const NAME = 'swagProductDiscount.detail.loaded';

    /**
     * @var SwagProductDiscountDetailCollection
     */
    protected $swagProductDiscounts;

    /**
     * @var TranslationContext
     */
    protected $context;

    public function __construct(SwagProductDiscountDetailCollection $swagProductDiscounts, TranslationContext $context)
    {
        $this->swagProductDiscounts = $swagProductDiscounts;
        $this->context = $context;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSwagProductDiscounts(): SwagProductDiscountDetailCollection
    {
        return $this->swagProductDiscounts;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [
            new SwagProductDiscountBasicLoadedEvent($this->swagProductDiscounts, $this->context),
        ];

        if ($this->swagProductDiscounts->getProduct_details()->count() > 0) {
            $events[] = new ProductDetailBasicLoadedEvent($this->swagProductDiscounts->getProduct_details(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}
