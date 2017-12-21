<?php
/** @var $this \yii\web\View */

use yii\grid\GridView;
use yii\data\ArrayDataProvider;

?>

<?php
$dataProvider = new ArrayDataProvider([
    'allModels' => $data,
    'pagination' => [
        'pageSize' => 50,
    ],
]);
?>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => 'Date',
            'value' => function ($model) {
                return $model['date'];
            }
        ],
        [
            'label' => 'Uploads',
            'value' => function ($model) {
                return $model['upload_cnt'];
            }
        ],
        [
            'label' => 'Downloads',
            'value' => function ($model) {
                return $model['download_cnt'];
            }
        ],
        [
            'label' => 'Top download',
            'value' => function ($model) {
                if ($model['top_file_download']['filename']) {
                    return sprintf('%s (%s)', $model['top_file_download']['filename'], $model['top_file_download']['download_cnt']);
                }
                return null;
            }
        ],
    ],
]);
?>