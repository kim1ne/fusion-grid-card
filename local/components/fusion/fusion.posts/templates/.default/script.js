BX.ready(function () {
    BX.addCustomEvent(window, 'Grid::beforeRequest', (gridData, requestParams) => {
        if (requestParams.gridId !== 'fusion_posts_grid_id') {
            return;
        }

        requestParams.data.apply_filter = 'Y';
        requestParams.data.clear_nav = 'Y';

        if (requestParams.url === '') {
            requestParams.url = '/local/components/fusion/fusion.posts/lazyload.ajax.php';
        }
    });
})
