var zoomer = {
    currentImage: null,
    backdrop: null,
    mainImage: null,
    init: function () {
        this.backdrop = document.createElement("div");
        this.backdrop.classList.add('zoomer-backdrop');
        this.backdrop.addEventListener('click', function (event) {
            zoomer.zoomOut(event);
        })
        document.body.append(this.backdrop);
        this.mainImage = document.createElement("img");
        this.backdrop.append(this.mainImage);
        var images = document.getElementsByClassName("zoomer");

        for (var i = 0; i < images.length; i++)
            images[i].addEventListener('click', function () {
                zoomer.zoomIn(event);
            });

        window.addEventListener('scroll', function () {
            zoomer.scrollListener();
        });
    },

    zoomIn: function (event) {
        this.backdrop.classList.add('zoomer-backdrop-active')
        this.currentImage = event.target;
        this.mainImage.setAttribute('src', this.currentImage.currentSrc);
    },
    zoomOut: function (event) {
        this.backdrop.classList.remove('zoomer-backdrop-active')
    },
    scrollListener: function () {
        if (this.backdrop.classList.contains('zoomer-backdrop-active')) {
            this.zoomOut();
        }
    }
}


zoomer.init();



