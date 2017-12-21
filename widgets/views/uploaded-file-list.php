<?php
/** @var $models \app\models\UploadedFileModel[] */

/** @var $currentModel \app\models\UploadedFileModel */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-md-3">
    <div class="list-group">
        <?= Html::a('Upload file', Url::to(['file/upload']), ['class' => 'list-group-item list-group-item-success']); ?>

        <?php foreach ($models as $model) : ?>
            <?php
            $aText = sprintf('%s.%s - %s', $model->file_name, $model->file_extension, $model->created_at);
            $aUrl = Url::to(['file/view', 'id' => $model->id]);
            $aOptions['class'] = 'list-group-item';

            if ($currentModel && $currentModel->id == $model->id) {
                $aOptions['class'] .= ' active';
            }
            ?>

            <?= Html::a($aText, $aUrl, $aOptions); ?>
        <?php endforeach; ?>
    </div>
</div>
