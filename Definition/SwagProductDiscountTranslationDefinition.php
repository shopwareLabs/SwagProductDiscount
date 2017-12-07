<?php

namespace SwagProductDiscount\Definition;

use Shopware\Api\Entity\EntityDefinition;
use Shopware\Api\Entity\EntityExtensionInterface;
use Shopware\Api\Entity\FieldCollection;
use SwagProductDiscount\Repository\SwagProductDiscountTranslationRepository;
use SwagProductDiscount\Collection\SwagProductDiscountTranslationBasicCollection;
use SwagProductDiscount\Struct\SwagProductDiscountTranslationBasicStruct;
use SwagProductDiscount\Event\SwagProductDiscountTranslation\SwagProductDiscountTranslationWrittenEvent;
use Shopware\Api\Entity\Field\StringField;
use Shopware\Api\Write\Flag\PrimaryKey;
use Shopware\Api\Entity\Field\FkField;
use Shopware\Api\Write\Flag\Required;
use Shopware\Shop\Definition\ShopDefinition;
use Shopware\Api\Entity\Field\ManyToOneAssociationField;

use SwagProductDiscount\Collection\SwagProductDiscountTranslationDetailCollection;
use SwagProductDiscount\Struct\SwagProductDiscountTranslationDetailStruct;
            

class SwagProductDiscountTranslationDefinition extends EntityDefinition
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
        return 'swag_product_discount_translation';
    }

    public static function getFields(): FieldCollection
    {
        if (self::$fields) {
            return self::$fields;
        }

        self::$fields = new FieldCollection([
            (new FkField('swag_product_discount_uuid', 'swagProductDiscountUuid', SwagProductDiscountDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            (new FkField('language_uuid', 'languageUuid', ShopDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name'))->setFlags(new Required()),
            new ManyToOneAssociationField('swagProductDiscount', 'swag_product_discount_uuid', SwagProductDiscountDefinition::class, false),
            new ManyToOneAssociationField('language', 'language_uuid', ShopDefinition::class, false)
        ]);

        foreach (self::$extensions as $extension) {
            $extension->extendFields(self::$fields);
        }

        return self::$fields;
    }

    public static function getRepositoryClass(): string
    {
        return SwagProductDiscountTranslationRepository::class;
    }

    public static function getBasicCollectionClass(): string
    {
        return SwagProductDiscountTranslationBasicCollection::class;
    }

    public static function getWrittenEventClass(): string
    {
        return SwagProductDiscountTranslationWrittenEvent::class;
    }

    public static function getBasicStructClass(): string
    {
        return SwagProductDiscountTranslationBasicStruct::class;
    }

    public static function getTranslationDefinitionClass(): ?string
    {
        return null;
    }


    public static function getDetailStructClass(): string
    {
        return SwagProductDiscountTranslationDetailStruct::class;
    }
    
    public static function getDetailCollectionClass(): string
    {
        return SwagProductDiscountTranslationDetailCollection::class;
    }

}