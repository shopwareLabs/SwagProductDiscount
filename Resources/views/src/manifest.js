import template from '../administration/product-detail.html';
import SwagProductDiscountService from './swag_product_discount.service';

Shopware.ComponentFactory.override('core-product-detail', {
    inject: [
        'httpClient'
    ],

    data() {
       return {
           product: {
               attributes: {
                   discount: {}
               }
           },
           discount: {
               uuid: null,
               discountPercentage: null,
               productDetailUuid: null
           }
       };
    },

    mounted() {
        this.$on('core-product-detail:load:after', this.productLoadAfter.bind(this));

        this.$on('core-product-detail:save:before', this.productSaveAfter.bind(this));

        this.discountService = SwagProductDiscountService(this.httpClient);
    },

    computed: {
        productDiscount: {
            get: function() {
                if(this.discount && this.discount.discountPercentage) {
                    return this.discount.discountPercentage;
                }
                return null;
            },
            set: function(newValue) {
                if(this.discount && this.discount.uuid) {
                    this.discount.discountPercentage = newValue;
                } else {
                    this.discount = {
                        productDetailUuid: this.product.mainDetailUuid,
                        discountPercentage: newValue
                    }
                }
            }
        }
    },

    methods: {
        productLoadAfter(product) {
            console.log('PRODCUT LOAD AFTER!!!');
            this.getProductDiscountData(product.mainDetailUuid);
        },

        productSaveAfter(component)  {
            // console.log(this.discount);
            this.updateDiscount(this.discount)
        },

        getProductDiscountData(productDetailUuid) {
            this.discountService.readByProductDetailUuid(productDetailUuid).then((response) => {
                this.discount = response.data[0];
            });
        },

        updateDiscount(discount) {
            this.discountService.update([discount]).then((response) => {
                console.log('Discount persisted', response.data);
            });
        }
    },

    template
});