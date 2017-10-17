export default function SwagProductDiscountService(client) {
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
        return client.put(url, payload).then((response) => {
            return response.data;
        });
    }
}
