var ecommerceAdmin = {
    orderChange: function (route, id, direction) {
        $.ajax({
            url: route,
            data: {id: id, direction: direction},
            success: function () {
                $.pjax.reload({container: '#items', timeout: 10000});
            },
            error: function (response) {
                proccessError(response);
            }
        });
    }
}