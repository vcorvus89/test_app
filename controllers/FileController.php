<?php

namespace app\controllers;

use Yii;
use app\models\FileUploadForm;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\UploadedFileModel;

class FileController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpload()
    {
        $model = new FileUploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->upload()) {
                return $this->redirect(Url::to(['file/index']));
            }
        }

        return $this->render('upload', compact('model'));
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (!($model = UploadedFileModel::findOne($id))) {
            throw new NotFoundHttpException(sprintf('File with id "%s" does not exist', $id));
        }

        if (!file_exists($model->getFilePath())) {
            throw new NotFoundHttpException(sprintf('File "%s.%s" does not exist', $model->file_name, $model->file_extension));
        }

        return $this->render('view', compact('model'));
    }
}