Note: This plugin only works with the [labs branch](https://github.com/shopware/shopware/tree/labs).

1. Refresh the plugin list: `bin/console plugin:update`
2. Install and activate the plugin:
`bin/console plugin:install SwagProductDiscount --activate`
3. Clear the cache: `./psh.phar cache`
4. If you have a fresh installation, initialize the Shopware administration: `./psh.phar administration:init`
5. Build the Shopware administration: `./psh.phar administration:build`