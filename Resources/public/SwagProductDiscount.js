webpackJsonp([3],{

/***/ 774:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__administration_product_detail_html__ = __webpack_require__(775);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__administration_product_detail_html___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__administration_product_detail_html__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__swag_product_discount_service__ = __webpack_require__(776);



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

        this.discountService = Object(__WEBPACK_IMPORTED_MODULE_1__swag_product_discount_service__["a" /* default */])(this.httpClient);
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

    template: __WEBPACK_IMPORTED_MODULE_0__administration_product_detail_html___default.a
});

/***/ }),

/***/ 775:
/***/ (function(module, exports) {

module.exports = "{% block core_product_detail_workspace_additional %}\n\n    <sw-card title=\"Abschlag\">\n\n        <sw-field label=\"Discount\" suffix=\"%\" v-model.number=\"productDiscount\"></sw-field>\n\n    </sw-card>\n\n{% endblock %}";

/***/ }),

/***/ 776:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = SwagProductDiscountService;
function SwagProductDiscountService(client) {
    /**
     * Defines the return format for the API. The API can provide the content as JSON (json) or XML (xml).
     * @type {String} [returnFormat=json]
     */
    const returnFormat = 'json';
    return {
        readByProductDetailUuid,
        update
    };
    function readByProductDetailUuid(productDetailUuid) {
        const queryString = JSON.stringify({
            type: 'nested',
            queries: [
                {
                    type: 'term',
                    field: 'product_detail_uuid',
                    value: productDetailUuid
                }
            ]
        });
        const url = `swagProductDiscount.${returnFormat}?query=${queryString}`;
        return client.get(url).then((response) => {
            return response.data;
        });
    }
    function update(payload) {
        const url = `swagProductDiscount.${returnFormat}`;
        return client.patch(url, payload).then((response) => {
            return response.data;
        });
    }
}


/***/ })

},[774]);
//# sourceMappingURL=SwagProductDiscount.js.map