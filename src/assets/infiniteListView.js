if (typeof (pageSize) == 'undefined')
    pageSize = 0;

f12Listview = {
    pageSize: pageSize,
    currentPageSize: pageSize,
    next: function () {
        data = $('#f12-eccomerce-product-filter').serialize();
        this.currentPageSize += this.pageSize;
        data = data + '&per-page=' + this.currentPageSize;
        $.pjax.reload({
                'container': '#products',
                'timeout': 10000,
                'url': location.pathname,
                'data': data,
            }
        )
    }
}

