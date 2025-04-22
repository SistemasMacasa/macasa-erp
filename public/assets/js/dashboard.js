document.addEventListener('DOMContentLoaded', function () {
    // Gráfico Radial
    var radialOptions = {
        chart: {
            height: 250,
            type: 'radialBar'
        },
        series: [80, 60, 35],
        labels: ['Junio', 'Mayo', 'Abril'],
        colors: ['#34c38f', '#556ee6', '#f46a6a'],
        plotOptions: {
            radialBar: {
                offsetY: 0,
                hollow: {
                    margin: 5,
                    size: '30%'
                },
                dataLabels: {
                    name: {
                        fontSize: '14px'
                    },
                    value: {
                        show: false
                    }
                }
            }
        },
        legend: {
            show: true,
            position: 'bottom'
        }
    };
    var radialChart = new ApexCharts(document.querySelector("#radialBarBottom"), radialOptions);
    radialChart.render();

    // Gráfico de líneas
    var lineOptions = {
        chart: {
            height: 250,
            type: 'line',
            toolbar: {
                show: false
            }
        },
        series: [
            {
                name: 'Cotizaciones',
                data: [10, 20, 15, 30, 25, 35, 40]
            },
            {
                name: 'Ventas',
                data: [5, 15, 10, 20, 18, 30, 25]
            }
        ],
        xaxis: {
            categories: ['01', '05', '10', '15', '20', '25', '30']
        },
        colors: ['#556ee6', '#f46a6a']
    };
    var lineChart = new ApexCharts(document.querySelector("#lineChart"), lineOptions);
    lineChart.render();

    // Barras de progreso
function renderProgress(selector, value, colorFrom, colorTo) {
    const options = {
        chart: {
            height: 60,
            type: "bar",
            sparkline: { enabled: true }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '100%'
            }
        },
        series: [{
            name: 'Alcance',
            data: [value]
        }],
        xaxis: {
            categories: ['Alcance'],
            max: 100
        },
        colors: [colorFrom],
        tooltip: {
            enabled: false
        },
        fill: {
            type: "gradient",
            gradient: {
                shade: "light",
                type: "horizontal",
                shadeIntensity: 0.5,
                gradientToColors: [colorTo],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1
            }
        }
    };

    const el = document.querySelector(selector);
    new ApexCharts(el, options).render();
}


    renderProgress("#progress1", 60, '#00c6ff', '#0072ff');
    renderProgress("#progress2", 15, '#f093fb', '#f5576c');
});
