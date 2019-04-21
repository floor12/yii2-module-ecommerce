var registerGoogleTagEvents = true;

f12Tag = {
    log: true,

    registerEvent: function (eventName, data) {
        if (this.log) {
            console.log(eventName);
            console.log(data);
        }
        dataLayer.push({
            event: eventName,
            ecommerce: {
                currencyCode: 'RUB',
                eventName: {
                    products: data
                }
            }
        });
    },

    checkout: function (products) {
        this.registerEvent('checkout', products);
    },

    productQuantity: function (product) {
        this.registerEvent('quantity', product);
    },

    productsListed: function (products) {
        this.registerEvent('impressions', products);
    },

    productView: function (product) {
        this.registerEvent('detail', product);
    },

    productAddToCart: function (product) {
        this.registerEvent('add', product);
    },

    productremoveFromCart: function (data) {
        this.registerEvent('remove', product);
    },

    productsCheckout: function (products) {
        this.registerEvent('checkout', products);
    },

    productPurchase: function (order, products) {
        if (this.log) {
            console.log('purchase');
            console.log(order);
            console.log(products);
        }
        dataLayer.push({
            event: 'purchase',
            ecommerce: {
                currencyCode: 'RUB',
                purchase:
                    {
                        actionField: {
                            order
                        },
                        products: {
                            products
                        }
                    }
            }
        })


    }
}