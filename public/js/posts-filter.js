let params = {
    page: 1,
    curreny: 'usd',
    cost_from: null,
    cost_to: null,
    country: null,
    conditions: [],
    types: [],
    is_urgent: [],
    is_import: [],
    search: '',
    sorting: 'latest'
};

$(document).ready(function () {
    let request;
    let timeout;

    $('.filter-block input').keyup(function (e) {
        let name = $(this).attr('name');
        let val = $(this).val();
        params[name] = val;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
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
        } else if (filterType=='radio') {
            params[name] = val;
        }
        filter();
    });

    $('.filter-block select').on('selectmenuchange', function() {
        let name = $(this).attr('name');
        if (!name) {
            return;
        }
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

    $(document).on('posts:filter', function(e) {
        filter();
    });

    function getParameterByName(name, url) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function filter() {
        console.log('filter'); //! LOG
        clearTimeout(timeout);
        prevParams = JSON.parse(JSON.stringify(params));
        fullLoader();

        if (request) {
            request.abort();
        }
        request = $.ajax({
            type: 'get',
            data: params,
            success: (response)=>{
                request = null;
                $('.searched-content').empty().append(response.data.view);
                $(document).trigger('posts:filtered');
                $('.searched-amount').text(response.data.total);
                fullLoader(false);
            },
            error: function(response) {
                if (response.statusText == 'abort') {
                    // reqeust been aborted by js
                    return;
                }
                request = null;
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

    setItialParams();
});
