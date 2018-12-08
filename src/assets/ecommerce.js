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

$(document).on('change', '#f12-eccomerce-item-filter', function () {
    submitForm($(this));
})

$(document).on('change', '#order-delivery_type_id', function () {
    ecommerceAddressCheck();
})

$(document).on('change', '#order-city', function () {
    cityReplace();
})

$(document).on('keyup', '#f12-eccomerce-item-filter', function () {
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
        url: '/shop/cart/delivery-cost',
        data: {'city_id': city_id, 'weight': weight},
        error: function (response) {
            proccessError(response)
        },
        success: function (response) {
            if (response)
                $('#f12-delivery-cost span').html(response);
        }

    })
}