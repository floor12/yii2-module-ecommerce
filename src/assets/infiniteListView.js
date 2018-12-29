
f12Listview = {
    pageSize: pageSize,
    currentPageSize: pageSize,
    next: function () {
        data = $('#f12-eccomerce-item-filter').serialize();
        this.currentPageSize += this.pageSize;
        data = data + '&per-page=' + this.currentPageSize;
        $.pjax.reload({
                'container': '#items',
                'url': location.pathname,
                'data': data,
            }
        )
    }
}

