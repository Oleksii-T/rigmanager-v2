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
                let colors = [
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

