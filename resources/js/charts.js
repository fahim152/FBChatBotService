function showChart(selector_name, chart_title, chart_data) {
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(chart_data);

        var options = {
            title: chart_title,
            curveType: 'function',
        };

        var chart = new google.visualization.LineChart(document.getElementById(selector_name));

        chart.draw(data, options);
    }
}
function loadResidenceChart(type = 'month') {
    if ($("#prc_chart").length) {
        $.ajax({
            url: '/charts/residence',
            type: 'POST',
            data: {
                type
            }
        }).done(function (response) {
            let data = response.data;
            let selector_name = 'prc_chart';
            showChart(selector_name, 'Residence Chart', data);
        });
    }
}

function loadForeignerChart(type = 'month') {
    if ($("#ftp_chart").length) {
        $.ajax({
            url: '/charts/foreigner',
            type: 'POST',
            data: {
                type
            }
        }).done(function (response) {
            let data = response.data;
            let selector_name = 'ftp_chart';
            showChart(selector_name, 'Foreigner Chart', data);
        });
    }
}

$('#chartResidenceSelector').change(function(){
  var option = $(this).find('option:selected').val();
  loadResidenceChart(option);
});

$('#chartForeignerSelector').change(function(){
  var option = $(this).find('option:selected').val();
  loadForeignerChart(option);
});

$(document).ready(function(){
    loadResidenceChart('month');
    loadForeignerChart('month');
});
