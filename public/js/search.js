$(document).ready(function () {
    let params = {
        page: 1,
        curreny: 'usd',
        cost_from: null,
        cost_to: null,
        region: null,
        conditions: [],
        types: [],
        legal_types: [],
        is_urgent: [],
        is_import: [],
        sorting: 'latest'
    };
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
        // console.log(params, name, val); //! LOG
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
                $('.searched-amount').text(response.data.total);
                fullLoader(false);
            },
            error: function(response) {
                if (response.statusText == 'abort') {
                    return;
                }
                request = null;
                showError();
                fullLoader(false);
            }
        });
    }
});
