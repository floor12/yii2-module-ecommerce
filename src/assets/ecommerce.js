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
