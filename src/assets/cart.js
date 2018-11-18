/**
 * Created by evgenygoryaev on 13/08/2017.
 */

function updateCartCount() {

    res = document.cookie.match(/cart-/ig);

    if (res)
        total = res.length;
    else
        total = 0;

    $('.cart-link span.counter').html('[ ' + total + ' ]');

    if (total > 0) {
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

        if (confirm('Этот товар находится в корзине, вы хотите удалть его?')) {
            $(this).removeClass('btn-primary')
            $(this).addClass('btn-default')
            $(this).attr('title', 'Добавить в избранное')
            $.removeCookie(name, {expires: 31, path: '/'});
            updateCartCount();


            parent = $(this).parents('.modal-body table tbody');
            if (parent.length) {
                block = $(this).parents('tr');
                block.fadeOut(300, function () {
                    block.parent().remove();
                });
                $('#items div[data-key="' + id + '"]').find('a.cart').removeClass('btn-primary');

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

            console.log(shadow_object);

            //анимируем его перемещение

            cart_pos_x = $('.cart-link').offset().left;

            shadow_object.animate({
                top: 10,
                left: cart_pos_x + 10,
                width: 10,
                height: 10,
                opacity: 0.5
            }, 500, function () {
                shadow_object.remove();
                updateCartCount();
            });
        } else
            updateCartCount();


    }

})

updateCartCount();