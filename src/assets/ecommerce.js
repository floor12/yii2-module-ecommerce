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


function addToCart(id) {
    console.log(id);
}