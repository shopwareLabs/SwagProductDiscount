Note: This plugin only works with the [labs branch](https://github.com/shopware/shopware/tree/labs).

1. Update the plugin list `bin/console plugin:update`
2. Install and activate the plugin:
`bin/console plugin:install SwagProductDiscount --activate`
2. Clear the cache: `./psh.phar cache`
3. Rebuild the Shopware administration: `./psh administration:build`
