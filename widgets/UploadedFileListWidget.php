<?php

namespace app\widgets;

use app\models\UploadedFileModel;
use yii\base\Widget;

class UploadedFileListWidget extends Widget
{
    /**
     * @var UploadedFileModel
     */
    public $currentModel;

    /**
     * @var null|UploadedFileModel[]
     */
    public $models = null;

    /**
     * @return void
     */
    public function init()
    {
        if (is_null($this->models)) {
            $this->models = UploadedFileModel::find()->orderBy(['created_at' => SORT_DESC])->all();
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('uploaded-file-list', ['models' => $this->models, 'currentModel' => $this->currentModel]);
    }
}