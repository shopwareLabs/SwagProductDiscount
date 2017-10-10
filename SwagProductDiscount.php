<?php declare(strict_types=1);

namespace SwagProductDiscount;

use Doctrine\DBAL\Connection;
use Shopware\Framework\Plugin\Context\InstallContext;
use Shopware\Framework\Plugin\Context\UninstallContext;
use Shopware\Framework\Plugin\Plugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SwagProductDiscount extends Plugin
{

    public function __construct($active = true)
    {
        parent::__construct($active);

    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('write-resources.xml');
        $loader->load('read-services.xml');
        $loader->load('services.xml');
    }

    public function install(InstallContext $context)
    {
        /** @var Connection $dbal */
        $dbal = $this->container->get('dbal_connection');
        $sql = <<<SQL
CREATE TABLE `swag_product_discount` (
    `uuid` varchar(42) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
    `product_detail_uuid` VARCHAR(42) NOT NULL UNIQUE,
    `discount_percentage` DOUBLE NOT NULL DEFAULT 0,
    PRIMARY KEY `uuid` (`uuid`),
    FOREIGN KEY (`product_detail_uuid`) REFERENCES product_detail (`uuid`)
);

CREATE TABLE `swag_product_discount_translation` (
    `swag_product_discount_uuid` varchar(42) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
    `language_uuid` varchar(42) NOT NULL,
    `name` varchar(200) NOT NULL,
    PRIMARY KEY `swag_product_discount_uuid_language_uuid` (`swag_product_discount_uuid`, `language_uuid`),
    FOREIGN KEY (`swag_product_discount_uuid`) REFERENCES `swag_product_discount` (`uuid`),
    FOREIGN KEY (`language_uuid`) REFERENCES `shop` (`uuid`)
);
SQL;

        $dbal->exec($sql);
        parent::install($context);
    }

    public function uninstall(UninstallContext $context)
    {
        /** @var Connection $dbal */
        $dbal = $this->container->get('dbal_connection');
        $sql = <<<SQL
DROP TABLE swag_product_discount_translation;
DROP TABLE swag_product_discount;
SQL;
        $dbal->exec($sql);
        parent::uninstall($context);
    }
}
