/**
 * Created by evgenygoryaev on 13/08/2017.
 */

var itemOptionsurl;

var currentItem = [];

var f12shop = {
    total_in_cart: 0,

    showCart: function () {
        showForm('/shop/frontend/cart', {}, {}, true);
    },

    updateCartItems: function () {
        $.pjax.reload({
                'url': '/shop/frontend/cart',
                'container': '#cart-content',
                'replace': false,
                'push': false,
                'timeout': 10000,
            }
        );
    },

    updateCartCount: function () {
        res = document.cookie.match(/cart-/ig);

        if (res)
            this.total_in_cart = res.length;
        else
            this.total_in_cart = 0;

        $('.cart-link span.counter').html(this.total_in_cart);

        if (this.total_in_cart > 0) {
            $('.cart-link span.counter').show();
            $('.cart-link').addClass('cart-link-active');
        } else {
            $('.cart-link span.counter').hide();
            $('.cart-link').removeClass('cart-link-active');
        }
    },
    addVariationToCart: function (event) {
        clickObject = $(event.target);
        if (event.target.nodeName != 'BUTTON')
            clickObject = clickObject.parents('button');
        variationId = $(clickObject).data('id');
        if (!variationId) {
            $('.parameters-selector').addClass('alerted');
            return;
        }
        btn = $(event.target);
        console.log(variationId);
        this.addToCart(variationId, btn);
    },

    addToCart: function (id, btn, quantity = 1) {
        name = "cart-" + id;

        if (quantity < 0)
            quantity = -1 * quantity;

        if ($.cookie(name))
            quantity = parseInt($.cookie(name)) + parseInt(quantity)

        $.cookie(name, quantity, {expires: 31, path: '/'});
        $('.proceed-to-checkout').fadeIn(300);


        full_block = btn.parents('.cart-shadow');
        if (full_block.length !== 0) {
            pos_y = full_block.offset().top - $(window).scrollTop();
            pos_x = full_block.offset().left;
            shadow_object = $("<div>").addClass('cart-shadow-object').appendTo('body');
            shadow_object.width(full_block.width()).height(full_block.height() - 30).css({top: pos_y, left: pos_x});

            //анимируем его перемещение
            cart_pos_x = $('.cart-link').offset().left;
            cart_pos_y = $('.cart-link').offset().top - $(window).scrollTop();

            shadow_object.animate({
                top: cart_pos_y,
                left: cart_pos_x + 20,
                width: 10,
                height: 10,
                opacity: 0.3
            }, 500, function () {
                shadow_object.remove();
                f12shop.updateCartCount();
            });
        } else
            f12shop.updateCartCount();

        if (typeof (registerGoogleTagEvents) != 'undefined' && registerGoogleTagEvents == true)
            f12Tag.productAddToCart([currentItem]);
        this.showCart();
    },
    removeItemFromCart: function (id) {
        name = "cart-" + id;
        $.removeCookie(name, {expires: 31, path: '/'});
        f12shop.updateCartCount();
        f12shop.updateCartItems();

    },
    removeFromCart: function (id, btn) {
        name = "cart-" + id;
        if (true) { // если понадобится, можно сделать какой-нибудь confirm() тут
            btn.removeClass('btn-primary')
            btn.addClass('btn-default')
            btn.attr('title', 'Добавить в корзину')
            $.removeCookie(name, {expires: 31, path: '/'});
            this.updateCartCount();

            if (this.total_in_cart == 0)
                $('.proceed-to-checkout').fadeOut(300);

            parent = btn.parents('.modal-body table tbody');
            if (parent.length) {
                block = $(this).parents('tr');
                block.fadeOut(300, function () {
                    f12shop.updateCartItems();
                });

                // $('#items s.cart[data-key="' + id + '"]').removeClass('btn-primary').addClass('btn-default');

                $.each($("a.cart"), function (key, val) {
                    a = $(val);
                    if (a.data('id') == id) {
                        a.removeClass('btn-primary')
                        a.addClass('btn-default')
                        a.attr('title', 'Добавить в избранное')
                    }
                });

                if (parent.find('tr').length == 0)
                    cancelModalEditSilent();
            }
        }
    },

    updateCartQuantity: function (id, quantity) {
        name = "cart-" + id;

        if (quantity < 0)
            quantity = -1 * quantity;

        let quantityOld = $.cookie(name);
        let product = {};
        let dif = quantity - quantityOld;

        $.ajax({
            url: '/shop/frontend/cart/product',
            data: {id: id},
            success: function (response) {
                product = response;
                product.quantity = parseInt(Math.abs(dif));
                if (registerGoogleTagEvents == true) {
                    if (dif > 0)
                        f12Tag.productAddToCart([product])
                    else
                        f12Tag.productRemoveFromCart([product])
                }
            }
        });


        if (quantity == 0) {
            $.removeCookie(name, {expires: 31, path: '/'});
        } else {
            $.cookie(name, quantity, {expires: 31, path: '/'});
        }
        f12shop.updateCartCount();
        f12shop.updateCartItems();


    },

    optionsRequest: function () {
        form = $('#add-to-cart-ajax');
        data = form.serialize();
        $.ajax({
            url: itemOptionsurl,
            data: data,
            dataType: 'json',
            success: function (response) {
                $('#price-actual').html(response.message);
                if (response.status == 0) {
                    $('#addToCartAjaxBtn').addClass('disabled');
                } else {
                    $('#addToCartAjaxBtn').removeClass('disabled');
                }
                currentItem = response.gtagData;
            },
            error: function (response) {
                processError(response);
            }
        })
    }
}


$(document).on('click', '.cart-link', function () {
    f12shop.showCart();
})

$(document).on('change', '#add-to-cart-ajax', function () {
    f12shop.optionsRequest();
})

cartTimeout = setTimeout(function () {
}, 500);

$(document).on('change', 'input.cart-counter', function () {
    clearTimeout(cartTimeout);
    input = $(this);
    quantity = input.val();
    id = input.data('id');
    cartTimeout = setTimeout(function () {
        f12shop.updateCartQuantity(id, quantity);
    }, 1400);

})


$(document).on('click', 'a.cart-delete', function () {
    id = $(this).data('id');
    name = "cart-" + id;
    let quantity = $.cookie(name);

    console.log(quantity);

    if ($.cookie(name)) {

        if (typeof (registerGoogleTagEvents) != 'undefined' && registerGoogleTagEvents == true)
            $.ajax({
                url: '/shop/frontend/cart/product',
                data: {id: id},
                success: function (response) {
                    product = response;
                    product.quantity = parseInt(quantity);
                    f12Tag.productRemoveFromCart([product]);
                }
            });

        f12shop.removeItemFromCart(id)
    }
})


$(document).on('click', 'a.cart', function () {

    id = $(this).data('id');
    name = "cart-" + id;

    if ($.cookie(name)) {
        f12shop.removeFromCart(id, $(this))
    } else {
        f12shop.addToCart(id, $(this))
    }

})

f12shop.updateCartCount();


