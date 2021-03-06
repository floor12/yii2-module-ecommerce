/**
 * Created by evgenygoryaev on 13/08/2017.
 */

function updateFavCount() {

    res = document.cookie.match(/fav-/ig);

    if (res)
        total = res.length;
    else
        total = 0;

    $('.favorites-link span.counter').html(total);

    if (total > 0) {
        $('.favorites-link span.counter').show();
        $('.favorites-link').addClass('favorites-link-active');
    } else {
        $('.favorites-link span.counter').hide();
        $('.favorites-link').removeClass('favorites-link-active');

    }
}

function showFavorites() {
    var regexp = /fav-(\d+)=1/ig;
    var result;
    var res = [];


    while (result = regexp.exec(document.cookie)) {
        res.push(result[1])
    }
    if (res.length) {
        showForm('/shop/frontend/favorites');
        $('#modaledit-modal div.modal-content div.modal-body').html('');
        setTimeout(function () {
            $.each(res, function (key, val) {
                $.ajax({
                    url: '/shop/frontend/favorites/product',
                    data: {id: val},
                    success: function (response) {
                        $('#modaledit-modal div.modal-content div.modal-body').append(response);
                    }
                })
            })
        }, 200);

    } else
        info('У вас нет избранных объявлений.', 1);
}

$(document).on('click', '.favorites-link', function () {
    showFavorites();
})

$(document).on('click', 'button.fav', function () {
    id = $(this).data('id');
    name = "fav-" + id;
    if ($.cookie(name)) {
        $(this).removeClass('fav-active')
        $(this).attr('title', 'Добавить в избранное')
        $.removeCookie(name, {expires: 31, path: '/'});
        updateFavCount();
        parent = $(this).parents('.modal-body');
        console.log(parent);
        if (parent.length) {
            block = $(this).parent().find('.f12-ec-product');
            console.log(block);
            block.fadeOut(300, function () {
                block.parent().remove();
            });
            $('#items div[data-key="' + id + '"]').find('button.fav').removeClass('fav-active');

            if (parent.find('.f12-ec-product').length == 1)
                cancelModalEditSilent();
        }


    } else {
        $.cookie(name, 1, {expires: 31, path: '/'});
        $(this).addClass('fav-active');
        $(this).attr('title', 'Удалить из избранного');

        // создаем и позиционируем блок
        full_block = $(this).parent().find('.f12-ec-product');

        if (full_block.length !== 0) {
            pos_y = full_block.offset().top - $(window).scrollTop();
            pos_x = full_block.offset().left;
            shadow_object = $("<div>").addClass('favorites-shadow-object').appendTo('body');
            shadow_object.width(full_block.width()).height(full_block.height() - 30).css({top: pos_y, left: pos_x});

            //анимируем его перемещение

            cart_pos_x = $('.favorites-link').offset().left;
            cart_pos_y = $('.favorites-link').offset().top - $(window).scrollTop();

            shadow_object.animate({
                top: cart_pos_y,
                left: cart_pos_x + 20,
                width: 10,
                height: 10,
                opacity: 0.5
            }, 500, function () {
                shadow_object.remove();
                updateFavCount();
            });
        } else
            updateFavCount();
    }
})

updateFavCount();
