/**
 * Created by evgenygoryaev on 13/08/2017.
 */

var itemOptionsurl;

var f12shop = {
    total_in_cart: 0,

    showCart: function () {
        showForm('/shop/cart');
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

    addToCart: function (id, btn, quantity = 1) {
        name = "cart-" + id;
        
        if ($.cookie(name))
            quantity = parseInt($.cookie(name)) + parseInt(quantity)

        $.cookie(name, quantity, {expires: 31, path: '/'});
        $('.proceed-to-checkout').fadeIn(300);
        btn.addClass('btn-primary');
        btn.removeClass('btn-default')
        btn.attr('title', 'Удалить из корзины');

        // создаем и позиционируем блок
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


    },
    removeItemFromCart: function (id) {
        name = "cart-" + id;
        $.removeCookie(name, {expires: 31, path: '/'});
        this.showCart();
        this.updateCartCount();

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
                    showCart();
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

        if (quantity == 0) {
            $.removeCookie(name, {expires: 31, path: '/'});
        } else {
            $.cookie(name, quantity, {expires: 31, path: '/'});
        }
        f12shop.updateCartCount();
        f12shop.showCart();
    },

    optionsRequest: function () {
        console.log(11);
        form = $('#add-to-cart-ajax');
        data = form.serialize();
        $.ajax({
            url: itemOptionsurl,
            data: data,
            dataType: 'json',
            success: function (response) {
                $('#price-actual').html(response.message);
                if (response.status == 0) {
                    $('#addToCartAjaxBtn').removeAttr('disabled').attr('onclick', 'f12shop.addToCart(' + response.option_id + ', $(this)); return false;')
                } else {
                    $('#addToCartAjaxBtn').attr('disabled', 'disabled').removeAttr('onclick');
                }
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
$


$(document).on('change', 'input.cart-counter', function () {
    input = $(this);
    quantity = input.val();
    id = input.data('id');
    f12shop.updateCartQuantity(id, quantity);

})


$(document).on('click', 'a.cart-delete', function () {

    id = $(this).data('id');
    name = "cart-" + id;

    if ($.cookie(name)) {
        f12shop.removeItemFromCart(id)
    }
    ;
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


