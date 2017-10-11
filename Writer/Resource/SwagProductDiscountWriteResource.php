<?php declare(strict_types=1);

namespace SwagProductDiscount\Writer\Resource;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Write\Field\FkField;
use Shopware\Framework\Write\Field\FloatField;
use Shopware\Framework\Write\Field\ReferenceField;
use Shopware\Framework\Write\Field\SubresourceField;
use Shopware\Framework\Write\Field\TranslatedField;
use Shopware\Framework\Write\Field\UuidField;
use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\WriteResource;
use Shopware\ProductDetail\Writer\Resource\ProductDetailWriteResource;
use Shopware\Shop\Writer\Resource\ShopWriteResource;
use SwagProductDiscount\Event\SwagProductDiscountWrittenEvent;

class SwagProductDiscountWriteResource extends WriteResource
{
    protected const UUID_FIELD = 'uuid';
    protected const DISCOUNT_PERCENTAGE_FIELD = 'discountPercentage';
    protected const NAME_FIELD = 'name';

    public function __construct()
    {
        parent::__construct('swag_product_discount');

        $this->primaryKeyFields[self::UUID_FIELD] = (new UuidField('uuid'))->setFlags(new Required());
        $this->fields[self::DISCOUNT_PERCENTAGE_FIELD] = new FloatField('discount_percentage');
        $this->fields['productDetail'] = new ReferenceField('productDetailUuid', 'uuid', ProductDetailWriteResource::class);
        $this->fields['productDetailUuid'] = (new FkField('product_detail_uuid', ProductDetailWriteResource::class, 'uuid'))->setFlags(new Required());
//        $this->fields[self::NAME_FIELD] = new TranslatedField('name', ShopWriteResource::class, 'uuid');
//        $this->fields['translations'] = (new SubresourceField(SwagProductDiscountTranslationWriteResource::class, 'languageUuid'))->setFlags(new Required());
    }

    public function getWriteOrder(): array
    {
        return [
            ProductDetailWriteResource::class,
            self::class,
            SwagProductDiscountTranslationWriteResource::class,
        ];
    }

    public static function createWrittenEvent(array $updates, TranslationContext $context, array $rawData = [], array $errors = []): SwagProductDiscountWrittenEvent
    {
        $event = new SwagProductDiscountWrittenEvent($updates[self::class] ?? [], $context, $errors);

        unset($updates[self::class]);

        if (!empty($updates[ProductDetailWriteResource::class])) {
            $event->addEvent(ProductDetailWriteResource::createWrittenEvent($updates, $context));
        }
        if (!empty($updates[self::class])) {
            $event->addEvent(self::createWrittenEvent($updates, $context));
        }
        if (!empty($updates[SwagProductDiscountTranslationWriteResource::class])) {
            $event->addEvent(SwagProductDiscountTranslationWriteResource::createWrittenEvent($updates, $context));
        }

        return $event;
    }
}
