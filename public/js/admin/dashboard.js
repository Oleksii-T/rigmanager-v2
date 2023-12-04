var colors = [
    '#FF0000', // Bright Red
    '#0000FF', // Deep Blue
    '#00FF00', // Vivid Green
    '#FFFF00', // Bright Yellow
    '#800080', // Deep Purple
    '#FFA500', // Orange
    '#87CEEB', // Sky Blue
    '#FFC0CB', // Pink
    '#006400', // Dark Green
    '#D2691E', // Chocolate Brown
    '#007BA7', // Cerulean
    '#FF00FF', // Magenta
];

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
        usersPerCountry: {
            elem: $('#user-per-country'),
            hasDatasets: false,
            chart: new Chart($('#user-per-country'), {
                type: 'pie',
                data: {
                    datasets: [{backgroundColor: colors}]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {position: 'top',},
                        title: {display: false}
                    }
                }
            })
        },
        postsPerLocale: {
            elem: $('#posts-per-locale'),
            hasDatasets: false,
            chart: new Chart($('#posts-per-locale'), {
                type: 'pie',
                data: {
                    datasets: [{backgroundColor: colors}]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {position: 'top',},
                        title: {display: false}
                    }
                }
            })
        },
        notificationsByGroups: {
            elem: $('#notifications-by-groups'),
            hasDatasets: false,
            chart: new Chart($('#notifications-by-groups'), {
                type: 'pie',
                data: {
                    datasets: [{backgroundColor: colors}]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {position: 'top',},
                        title: {display: false}
                    }
                }
            })
        },
        mailerEmails: {
            elem: $('#mailer-emails'),
            hasDatasets: false,
            chart: new Chart($('#mailer-emails'), {
                type: 'line',
                data: {datasets: [{}]},
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
});

function getChartTimeOptions() {
    return {
        scales: {
            y: {
                beginAtZero: true,
                min: 0,
                ticks: {
                    stepSize: 1,
                }
            },
            x: {
                type: 'time',
                time: {
                    unit: 'day',
                }
            }
        }
    };
}

function drawChart(chartItem) {
    let period = chartItem.elem.closest('.card').find('input[name=period]').val();
    $.ajax({
        url: chartItem.elem.data('url'),
        type: 'get',
        data: {period},
        success: (response)=>{
            let data = response.data;

            if (chartItem.hasDatasets) {
                let i = 0;
                for (const key in data) {
                    data[key].backgroundColor = colors[i];
                    data[key].borderColor = colors[i];
                    i++;
                }

                chartItem.chart.data.datasets = data;
            } else if (chartItem.chart.config._config.type == 'pie') {
                chartItem.chart.data.labels = Object.keys(data);
                chartItem.chart.data.datasets[0].data = Object.values(data);
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
