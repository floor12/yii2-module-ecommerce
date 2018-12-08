/**
 * Created by evgenygoryaev on 13/08/2017.
 */

var total_in_cart = 0;

function updateCartCount() {

    res = document.cookie.match(/cart-/ig);

    if (res)
        total_in_cart = res.length;
    else
        total_in_cart = 0;

    $('.cart-link span.counter').html(total_in_cart);

    if (total_in_cart > 0) {
        $('.cart-link span.counter').show();
        $('.cart-link').addClass('cart-link-active');
    } else {
        $('.cart-link span.counter').hide();
        $('.cart-link').removeClass('cart-link-active');

    }
}

function showCart() {
    showForm('/shop/cart');
}

$(document).on('click', '.cart-link', function () {
    showCart();
})


$(document).on('change', 'input.cart-counter', function () {
    input = $(this);
    quantity = input.val();
    id = input.data('id');
    name = "cart-" + id;

    if (quantity == 0) {
        $.removeCookie(name, {expires: 31, path: '/'});
    } else {
        $.cookie(name, quantity, {expires: 31, path: '/'});
    }
    updateCartCount();
    showCart();
})

$(document).on('click', 'a.cart', function () {
    id = $(this).data('id');
    name = "cart-" + id;
    if ($.cookie(name)) {

        if (true) { // если понадобится, можно сделать какой-нибудь confirm() тут
            $(this).removeClass('btn-primary')
            $(this).addClass('btn-default')
            $(this).attr('title', 'Добавить в избранное')
            $.removeCookie(name, {expires: 31, path: '/'});
            updateCartCount();

            if (total_in_cart == 0)
                $('.proceed-to-checkout').fadeOut(300);

            parent = $(this).parents('.modal-body table tbody');
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


    } else {
        $.cookie(name, 1, {expires: 31, path: '/'});
        $('.proceed-to-checkout').fadeIn(300);
        $(this).addClass('btn-primary');
        $(this).removeClass('btn-default')
        $(this).attr('title', 'Удалить из избранного');

        // создаем и позиционируем блок
        full_block = $(this).parents('tr');


        if (full_block.length !== 0) {
            pos_y = full_block.offset().top - $(window).scrollTop();
            pos_x = full_block.offset().left;
            shadow_object = $("<div>").addClass('cart-shadow-object').appendTo('body');
            shadow_object.width(full_block.width()).height(full_block.height() - 30).css({top: pos_y, left: pos_x});

            //анимируем его перемещение
            cart_pos_x = $('.cart-link').offset().left;
            cart_pos_y = $('.cart-link').offset().top - $(window).scrollTop();

            console.log(cart_pos_y);

            shadow_object.animate({
                top: cart_pos_y,
                left: cart_pos_x + 20,
                width: 10,
                height: 10,
                opacity: 0.3
            }, 500, function () {
                shadow_object.remove();
                updateCartCount();
            });
        } else
            updateCartCount();


    }

})

updateCartCount();


