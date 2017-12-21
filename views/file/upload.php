<?php
/** @var $this \yii\web\View */
/** @var $model \app\models\FileUploadForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>

<?= $form->field($model, 'file')->fileInput(); ?>

<?= Html::submitButton('Submit', ['class' => 'btn btn-success']); ?>
<?= Html::a('Cancel', Url::to(['file/index']), ['class' => 'btn btn-warning']); ?>

<?php ActiveForm::end(); ?>
