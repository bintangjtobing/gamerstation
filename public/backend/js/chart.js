

var options = {
    series: [{
        name: 'Add Money',
        color: "#5A5278",
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
    }, {
        name: 'Money Out',
        color: "#6F6593",
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
    }, {
        name: 'Total Balance',
        color: "#8075AA",
        data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
    }],
    chart: {
        type: 'bar',
        toolbar: {
            show: false
        },
        height: 325
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            borderRadius: 5,
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    yaxis: {
        title: {
            text: '$ (thousands)'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$ " + val + " thousands"
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chart3"), options);
chart.render();

var chart4 = $('#chart4');
var chart_four = chart4.data('chart_four');

var options = {
    series: chart_four,
    chart: {
        width: 350,
        type: 'pie'
    },
    colors: ['#5A5278', '#6F6593', '#8075AA', '#A192D9'],
    labels: ['Active', 'Unverified', 'Banned', 'All'],
    responsive: [{
        breakpoint: 1480,
        options: {
            chart: {
                width: 280
            },
            legend: {
                position: 'bottom'
            }
        },
        breakpoint: 1199,
        options: {
            chart: {
                width: 380
            },
            legend: {
                position: 'bottom'
            }
        },
        breakpoint: 575,
        options: {
            chart: {
                width: 280
            },
            legend: {
                position: 'bottom'
            }
        }
    }],
    legend: {
        position: 'bottom'
    },
};

var chart = new ApexCharts(document.querySelector("#chart4"), options);
chart.render();



var options = {
    series: chart_four,
    chart: {
        width: 350,
        type: 'donut',
    },
    colors: ['#5A5278', '#6F6593', '#8075AA', '#A192D9'],
    labels: ['Today', '1 week', '1 month', '1 year'],
    legend: {
        position: 'bottom'
    },
    responsive: [{
        breakpoint: 1600,
        options: {
            chart: {
                width: 100,
            },
            legend: {
                position: 'bottom'
            }
        },
        breakpoint: 1199,
        options: {
            chart: {
                width: 380
            },
            legend: {
                position: 'bottom'
            }
        },
        breakpoint: 575,
        options: {
            chart: {
                width: 280
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};

var chart = new ApexCharts(document.querySelector("#chart5"), options);
chart.render();

// pie-chart
$(function () {
    $('#chart6').easyPieChart({
        size: 80,
        barColor: '#f05050',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#f050505a',
        lineCap: 'circle',
        animate: 3000
    });
});

$(function () {
    $('#chart7').easyPieChart({
        size: 80,
        barColor: '#10c469',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#10c4695a',
        lineCap: 'circle',
        animate: 3000
    });
});
$(function () {
    $('#chart77').easyPieChart({
        size: 80,
        barColor: '#10c469',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#10c4695a',
        lineCap: 'circle',
        animate: 3000
    });
});
$(function () {
    $('#chart78').easyPieChart({
        size: 80,
        barColor: '#10c469',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#10c4695a',
        lineCap: 'circle',
        animate: 3000
    });
});
$(function () {
    $('#chart79').easyPieChart({
        size: 80,
        barColor: '#10c469',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#10c4695a',
        lineCap: 'circle',
        animate: 3000
    });
});
$(function () {
    $('#chart80').easyPieChart({
        size: 80,
        barColor: '#10c469',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#10c4695a',
        lineCap: 'circle',
        animate: 3000
    });
});

$(function () {
    $('#chart8').easyPieChart({
        size: 80,
        barColor: '#ffbd4a',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#ffbd4a5a',
        lineCap: 'circle',
        animate: 3000
    });
});

$(function () {
    $('#chart9').easyPieChart({
        size: 80,
        barColor: '#ff8acc',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#ff8acc5a',
        lineCap: 'circle',
        animate: 3000
    });
});

$(function () {
    $('#chart10').easyPieChart({
        size: 80,
        barColor: '#7367f0',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#7367f05a',
        lineCap: 'circle',
        animate: 3000
    });
});

$(function () {
    $('#chart11').easyPieChart({
        size: 80,
        barColor: '#1e9ff2',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#1e9ff25a',
        lineCap: 'circle',
        animate: 3000
    });
});

$(function () {
    $('#chart12').easyPieChart({
        size: 80,
        barColor: '#5a5278',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#5a52785a',
        lineCap: 'circle',
        animate: 3000
    });
});

$(function () {
    $('#chart13').easyPieChart({
        size: 80,
        barColor: '#ADDDD0',
        scaleColor: false,
        lineWidth: 5,
        trackColor: '#ADDDD05a',
        lineCap: 'circle',
        animate: 3000
    });
});
