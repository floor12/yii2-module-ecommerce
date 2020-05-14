f12ecommerce = {
    generateOrdersReport() {
        data = $('#f12-eccomerce-order-filter').serialize();
        url = '/shop/admin/order/report?' + data;
        window.open(url, '_blank');
    }
}


timeout = setTimeout(function () {
});

$(document).on('change', '#f12-eccomerce-product-filter', function () {
    f12Listview.reload();
})

$(document).on('change', '#order-delivery_type_id', function () {
    ecommerceAddressCheck();
})

$(document).on('change', '#order-city', function () {
    cityReplace();
})



$(document).on('keyup', '#f12-eccomerce-product-filter', function () {
    clearInterval(timeout);
    timeout = setTimeout(function () {
        f12Listview.reload();
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


f12Listview = {
    next: function () {
        data = $('#f12-eccomerce-product-filter').serialize();
        currentOffset = $('.f12-ec-products > div.row > div').length;
        data = data + '&FrontendProductFilter[offset]=' + currentOffset;
        btn = $('#load-more');
        btn.find('span.downloading').show();
        btn.find('span.info').hide();
        $.ajax({
            url: location.pathname,
            data: data,
            success: function (response, status, data2) {
                total = data2.getResponseHeader('total-products');
                btn.find('span.downloading').hide();
                btn.find('span.info').show();
                $('.f12-ec-products > div.row').append(response);
                setObserverToProducts();
                url = location.pathname + '?' + data;
                history.pushState(data, $('title').html(), url);
                showedProducts = $('.f12-ec-products > div.row > div').length;
                if (total == showedProducts)
                    $('#load-more').hide();
                else
                    $('#load-more').show();
            }
        })
    },
    reload: function () {
        data = $('#f12-eccomerce-product-filter').serialize();
        $('.f12-ec-products > div.row').html('');
        btn = $('#load-more');
        btn.find('span.downloading').show();
        btn.find('span.info').hide();
        $.ajax({
            url: location.pathname,
            data: data,
            success: function (response) {
                btn.find('span.downloading').hide();
                btn.find('span.info').show();
                $('.f12-ec-products > div.row').html(response);
                setObserverToProducts();
                url = location.pathname + '?' + data;
                history.pushState({}, $('title').html(), url);
                showedProducts = $('.f12-ec-products > div.row > div').length;
                if (total == showedProducts)
                    $('#load-more').hide();
                else
                    $('#load-more').show();
            }
        })
    }
}

