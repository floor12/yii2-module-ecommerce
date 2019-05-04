var registerGoogleTagEvents = true;

f12Tag = {
    log: true,

    registerEvent: function (eventName, data) {
        if (this.log) {
            console.log(eventName);
            console.log(data);
        }

        event = {
            event: eventName,
            ecommerce: {
                currencyCode: 'RUB',
            }
        };

        event.ecommerce[eventName] = {products: data};

        console.log(event);
        dataLayer.push(event);
    },

    checkout: function (products) {
        this.registerEvent('checkout', products);
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

    productRemoveFromCart: function (product) {
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
                        products: products
                    }
            }
        })


    }
}