<?php

namespace SwagProductDiscount\Definition;

use Shopware\Api\Entity\EntityDefinition;
use Shopware\Api\Entity\EntityExtensionInterface;
use Shopware\Api\Entity\FieldCollection;
use SwagProductDiscount\Repository\SwagProductDiscountRepository;
use SwagProductDiscount\Collection\SwagProductDiscountBasicCollection;
use SwagProductDiscount\Struct\SwagProductDiscountBasicStruct;
use SwagProductDiscount\Event\SwagProductDiscount\SwagProductDiscountWrittenEvent;
use Shopware\Api\Entity\Field\UuidField;
use Shopware\Api\Write\Flag\PrimaryKey;
use Shopware\Api\Write\Flag\Required;
use Shopware\Api\Entity\Field\StringField;
use Shopware\Api\Entity\Field\FkField;
use Shopware\Product\Definition\ProductDefinition;
use Shopware\Api\Entity\Field\TranslatedField;
use Shopware\Api\Entity\Field\FloatField;
use Shopware\Api\Entity\Field\ManyToOneAssociationField;
use Shopware\Api\Entity\Field\TranslationsAssociationField;

use SwagProductDiscount\Collection\SwagProductDiscountDetailCollection;
use SwagProductDiscount\Struct\SwagProductDiscountDetailStruct;
            

class SwagProductDiscountDefinition extends EntityDefinition
{
    /**
     * @var FieldCollection
     */
    protected static $primaryKeys;

    /**
     * @var FieldCollection
     */
    protected static $fields;

    /**
     * @var EntityExtensionInterface[]
     */
    protected static $extensions = [];

    public static function getEntityName(): string
    {
        return 'swag_product_discount';
    }

    public static function getFields(): FieldCollection
    {
        if (self::$fields) {
            return self::$fields;
        }

        self::$fields = new FieldCollection([
            (new UuidField('uuid', 'uuid'))->setFlags(new PrimaryKey(), new Required()),
            (new FkField('product_uuid', 'productUuid', ProductDefinition::class))->setFlags(new Required()),
            (new TranslatedField(new StringField('name', 'name')))->setFlags(new Required()),
            new FloatField('discount_percentage', 'discountPercentage'),
            new ManyToOneAssociationField('product', 'product_uuid', ProductDefinition::class, false),
            (new TranslationsAssociationField('translations', SwagProductDiscountTranslationDefinition::class, 'swag_product_discount_uuid', false, 'uuid'))->setFlags(new Required())
        ]);

        foreach (self::$extensions as $extension) {
            $extension->extendFields(self::$fields);
        }

        return self::$fields;
    }

    public static function getRepositoryClass(): string
    {
        return SwagProductDiscountRepository::class;
    }

    public static function getBasicCollectionClass(): string
    {
        return SwagProductDiscountBasicCollection::class;
    }

    public static function getWrittenEventClass(): string
    {
        return SwagProductDiscountWrittenEvent::class;
    }

    public static function getBasicStructClass(): string
    {
        return SwagProductDiscountBasicStruct::class;
    }

    public static function getTranslationDefinitionClass(): ?string
    {
        return SwagProductDiscountTranslationDefinition::class;
    }


    public static function getDetailStructClass(): string
    {
        return SwagProductDiscountDetailStruct::class;
    }
    
    public static function getDetailCollectionClass(): string
    {
        return SwagProductDiscountDetailCollection::class;
    }

}