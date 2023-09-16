$(document).ready(function () {
    // set up object to store all charts
    let charts = {
        modelsCreated: {
            elem: $('#models-created'),
            hasDatasets: true,
            chart: new Chart($('#models-created'), {
                type: 'line',
                data: {datasets: []},
                options: getChartTimeOptions()
            })
        },

    };

    // initial draw of charts
    for (const key in charts) {
        // set up event to update chart data whet period updated
        charts[key].elem.closest('.card').find('input[name=period]').change(function(e) {
            drawChart(charts[key]);
        });

        drawChart(charts[key]);
    }

    $('[name="chart_data"]').change(function(e) {
        let date = $(this).val().split(' - ');
        console.log(` date`, date); //! LOG
        let url = $(this).closest('.card').find('canvas').data('url');
        console.log(` url `, url); //! LOG
        $.ajax({
            url,
            data: {
                from: date[0],
                to: date[1],
            },
            success: (response)=>{
                console.log(response);
            },
            error: function(response) {
                console.log(response);
            }
        });
    })
});

function getChartTimeOptions() {
    return {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'day',
                }
            }
        }
    };
}

function makeLineChart(elem) {
    return new Chart(elem, {
        type: 'line',
        data: {
            // labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Users created',
                data: [],
                // backgroundColor: [
                //     'rgba(255, 99, 132, 0.2)',
                //     'rgba(54, 162, 235, 0.2)',
                //     'rgba(255, 206, 86, 0.2)',
                //     'rgba(75, 192, 192, 0.2)',
                //     'rgba(153, 102, 255, 0.2)',
                //     'rgba(255, 159, 64, 0.2)'
                // ],
                // borderColor: [
                //     'rgba(255, 99, 132, 1)',
                //     'rgba(54, 162, 235, 1)',
                //     'rgba(255, 206, 86, 1)',
                //     'rgba(75, 192, 192, 1)',
                //     'rgba(153, 102, 255, 1)',
                //     'rgba(255, 159, 64, 1)'
                // ],
                // borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                }
            }
        }
    });
}

function drawChart(chartItem) {
    let period = chartItem.elem.closest('.card').find('input[name=period]').val().split(' - ');
    $.ajax({
        url: chartItem.elem.data('url'),
        type: 'get',
        data: {
            from: period[0],
            to: period[1]
        },
        success: (response)=>{
            let data = response.data;

            if (chartItem.hasDatasets) {
                let i = 0;
                let colors = [
                    '#1AA7F6', // blue
                    '#1DE183', // light green
                    '#e1bd1d', // yellow
                    '#2d1de1', // blue
                    '#e1371d', // red
                    '#1de1de', // cyan
                    '#a300ff', // purple
                    '#ff8a00', // orange
                ];

                for (const key in data) {
                    data[key].backgroundColor = colors[i];
                    data[key].borderColor = colors[i];
                    i++;
                }

                chartItem.chart.data.datasets = data;
            } else {
                chartItem.chart.data.datasets[0].data = data;
            }

            chartItem.chart.update();
        },
        error: function(response) {
            console.log(`chart data ajax error`, response);
        }
    });
}

