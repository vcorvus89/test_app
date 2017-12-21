<?php
/** @var $this \yii\web\View */
/** @var $model \app\models\UploadedFileModel */

use app\widgets\UploadedFileListWidget;

?>

<?php
$data = $model->getTableData();
?>

<div class="row">
    <?= UploadedFileListWidget::widget(['currentModel' => $model]); ?>

    <div class="col-md-9">
        <?= $this->render('_chart', compact('data')); ?>

        <?= $this->render('_table', compact('data')); ?>
    </div>
</div>