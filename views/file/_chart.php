<?php
/** @var $this \yii\web\View */
/** @var $data array */
?>

<?php
$chartData = [];

foreach ($data as $row) {
    $chartData[] = [$row['date'], $row['upload_cnt'], $row['download_cnt']];
}

$chartData = json_encode($chartData);
?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="chart_div"></div>

<?php
$js = <<< JS
google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawLineColors);

    function drawLineColors() {
        var data = new google.visualization.DataTable();
        var chartData = $chartData;

        data.addColumn('date', 'X');
        data.addColumn('number', 'Uploads');
        data.addColumn('number', 'Downloads');

        for (var i = 0, len = chartData.length; i < len; i++) {
            data.addRow([new Date(chartData[i][0]), chartData[i][1], chartData[i][2]]);
        }

        var options = {
            hAxis: {
                title: 'Date'
            },
            vAxis: {
                title: 'Count'
            },
            colors: ['#a52714', '#097138']
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
JS;
?>

<?php
$this->registerJs($js, \yii\web\View::POS_READY);
?>