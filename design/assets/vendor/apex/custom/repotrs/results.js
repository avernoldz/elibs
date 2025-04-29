var options = {
    series: [{
        name: 'Direct (Admin Borrowing Transactions)',  // Direct transactions by admin account
        type: 'area',
        data: [640, 505, 770, 415]  // Replace with actual data for total books borrowed via admin
    }, {
        name: 'Online (Student Borrowing Transactions)',  // Online transactions by student account
        type: 'line',
        data: [540, 405, 670, 315]  // Replace with actual data for total books borrowed via students
    }],
    chart: {
        height: 280,
        type: 'line',
        zoom: {
            enabled: false
        },
        toolbar: {
            show: false
        },
    },
    colors: ['#435EEF', '#FFC424'],
    stroke: {
        width: [0, 4],
        curve: 'smooth',
    },
    tooltip: {
        y: {
            formatter: function(val) {
                return val + " books";  // Displaying values as total books borrowed
            }
        }
    },
    grid: {
        borderColor: '#e0e6ed',
        strokeDashArray: 5,
        xaxis: {
            lines: {
                show: true
            }
        },   
        yaxis: {
            lines: {
                show: false,
            } 
        },
        padding: {
            top: 0,
            right: 20,
            bottom: 0,
            left: 20
        },
    },
    dataLabels: {
        enabled: true,
        enabledOnSeries: [1]
    },
    labels: ['Q1', 'Q2', 'Q3', 'Q4'],  // Quarterly labels, adjust as needed
    xaxis: {
        type: 'category',
        categories: ['Q1', 'Q2', 'Q3', 'Q4'],  // Adjust based on actual borrowing periods
    },
};

var chart = new ApexCharts(document.querySelector("#results"), options);
chart.render();
