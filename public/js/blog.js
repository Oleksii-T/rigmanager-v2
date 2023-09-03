let params = {
    page: 1,
    country: null,
    tags: [],
    search: '',
    sorting: 'latest'
};

$(document).ready(function () {
    let request;
    let timeout;

    Fancybox.bind("[data-fancybox='blogphotos']", {
        // Your custom options
    });

    $('.filter-block input').keyup(function (e) {
        let name = $(this).attr('name');
        let val = $(this).val();
        params[name] = val;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            params.page = 1;
            filter();
        }, 600)
    });

    $('.filter-block input').change(function (e) {
        let name = $(this).attr('name');
        if (!name || params[name] == undefined) {
            return;
        }
        let val = $(this).val();
        let filterType = $(this).attr('type');
        if (filterType=='checkbox') {
            let i = params[name].indexOf(val);
            if (i > -1) {
                params[name].splice(i, 1);
            } else {
                params[name].push(val);
            }
        } else {
            params[name] = val;
        }
        params.page = 1;
        filter();
    });

    $('.filter-block select').on('selectmenuchange', function() {
        // console.log(`event: selectmenuchange`); //! LOG
        let name = $(this).attr('name');
        if (!name) {
            return;
        }
        // console.log(` name: ` + name + ' val: ', val); //! LOG
        let val = $(this).val();
        params[name] = val;
        filter();
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = getParameterByName('page', $(this).attr('href'));
        params.page = page;
        window.scrollTo({ top: 0, behavior: "smooth" });
        filter();
    })

    function getParameterByName(name, url) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function filter() {
        clearTimeout(timeout);
        fullLoader();

        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: window.location.origin + window.location.pathname,
            type: 'get',
            data: params,
            success: (response)=>{
                request = null;
                $('.searched-content').empty().append(response.data.blogs);
                $('.searched-amount').text(response.data.total);
                pushState();
                fullLoader(false);
            },
            error: function(response) {
                if (response.statusText == 'abort') {
                    // reqeust been aborted by js
                    return;
                }
                request = null;
                fullLoader(false);
                showServerError(response);
            }
        });
    }

    function setItialParams() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        for(const entry of urlParams.entries()) {
            let key = entry[0].replace(/[0-9\]\[]/g, '');
            if (Array.isArray(params[key])) {
                params[key].push(entry[1]);
            } else {
                params[key] = entry[1];
            }
        }
    }

    function pushState() {
        let url = new URL(window.location.href);
        let oldHref = url.href;

        for (const key in params) {
            let val = params[key];
            if (Array.isArray(val)) {
                url.searchParams.delete(key+'[]');
                url.searchParams.delete(key+'[0]');
                url.searchParams.delete(key+'[1]');
                url.searchParams.delete(key+'[2]');
                url.searchParams.delete(key+'[3]');
                url.searchParams.delete(key+'[4]');
                val.forEach(v => {
                    url.searchParams.append(key+'[]', v);
                });
            } else if (key == 'page') {
                if (val != 1) {
                    url.searchParams.set(key, val);
                } else {
                    url.searchParams.delete(key);
                }
            } else if (key == 'sorting') {
                if (val != 'latest') {
                    url.searchParams.set(key, val);
                } else {
                    url.searchParams.delete(key);
                }
            } else if (val) {
                url.searchParams.set(key, val);
            } else {
                url.searchParams.delete(key);
            }
        }

        if (oldHref == url.href) {
            return;
        }

        $('.dynamic-url-params').each(function(index) {
            // console.log(`change link`); //! LOG
            let href = $(this).attr('href');

            // console.log(`  from ` + href); //! LOG
            href = new URL(href);
            href.search = url.search;
            href = href.href;
            // console.log(`  to ` + href); //! LOG
            $(this).attr('href', href);
        });

        url = url.href;

        window.history.pushState({path:url},'',url);
    }

    if ($('.searched-content').length) {
        fullLoader();
        setItialParams();
        filter();
    }
});
