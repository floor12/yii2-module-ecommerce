// index page

function initItemsIndexSwiper() {

    setTimeout(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            spaceBetween: 30,
            keyboardControl: true,
        });
    }, 500);
}


timeout = setTimeout(function () {
});

$(document).on('change', '#f12-eccomerce-product-filter', function () {
    submitForm($(this));
})

$(document).on('change', '#order-delivery_type_id', function () {
    ecommerceAddressCheck();
})

$(document).on('change', '#order-city', function () {
    cityReplace();
})

$(document).on('keyup', '#f12-eccomerce-product-filter', function () {
    clearInterval(timeout);
    form = $(this);
    timeout = setTimeout(function () {
        submitForm(form);
    }, 1000);
})

function submitForm(form) {
    method = form.attr('method');
    action = form.attr('action');
    container = form.data('container');
    $.pjax.reload({
        url: action,
        method: method,
        timeout: 10000,
        container: container,
        data: form.serialize()
    })

}


$(document).on('click', '.f12-ec-item .swiper-slide', function () {
    url = $(this).parents('.f12-ec-item').find('a.f12-ec-item-info').attr('href');
    offPageLeaving();
    document.location.href = url;
})

function ecommerceAddressCheck() {
    if ($('#order-delivery_type_id').val() == 0)
        $('.f12-ecommerce-address-section').fadeOut('200');
    else
        $('.f12-ecommerce-address-section').fadeIn('200');

    $.ajax({
        url: '/shop/frontend/cart/delivery-cost',
        data: {'type_id': $('#order-delivery_type_id').val()},
        error: function (response) {
            proccessError(response)
        },
        success: function (response) {
            if (response)
                $('#f12-delivery-cost span').html(response);
        }

    })
}

function cityReplace() {
    weight = 0;
    select = $('#order-city');
    city_id = select.val();
    $('#order-city_id').val(city_id);

    $.each($('input.cart-counter'), function (key, val) {
        input = $(val);
        weight = weight + input.data('weight') * input.val();
        weight = Math.round(weight * 100) / 100;
    })


    $.ajax({
        url: '/shop/frontend/cart/delivery-cost',
        data: {'city_id': city_id, 'weight': weight, 'type_id': $('#order-delivery_type_id').val()},
        error: function (response) {
            proccessError(response)
        },
        success: function (response) {
            if (response)
                $('#f12-delivery-cost span').html(response);
        }

    })
}

product = {
    switchImage: function (element) {
        element.addClass('active').siblings().removeClass('active');

        images = eval(element.data('sources'));

        $('#product-main-image').find('img').attr('src', images[0]);
        $('#product-main-image').find('source').attr('srcset', images[0] + ' 1x,' + images[01] + ' 2x');

    },
    parameterSelectorWidgetUpdate: function (id) {
        data = $('#f12-eccomerce-cart-form').serialize();
        $.ajax({
            url: '/shop/frontend/product/parameter-selector-widget?id=' + id,
            data: data,
            success: function (resonse) {
                $('#f12-eccomerce-cart-form').replaceWith(resonse);
            },
            error: function (response) {
                processError(response);
            }
        })
    }
}

$(document).on('change', '#f12-eccomerce-cart-form', function () {
    product.parameterSelectorWidgetUpdate($(this).data('id'));
})


$('.product-previews-block').on('click', 'a', function () {
    product.switchImage($(this));
    return false;
})