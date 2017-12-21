<?php

namespace app\models;

use Yii;
use app\behaviors\ContentProcessorBehavior;
use yii\db\Query;

/**
 * This is the model class for table "app_uploaded_file".
 *
 * @property integer $id
 * @property string $file_name
 * @property string $file_extension
 * @property string $created_at
 */
class UploadedFileModel extends \yii\db\ActiveRecord
{
    /**
     * @var null
     */
    public $fileContent = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_uploaded_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_name', 'file_extension'], 'required'],
            [['created_at'], 'safe'],
            [['file_name'], 'string', 'max' => 255],
            [['file_extension'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'File Name',
            'file_extension' => 'File Extension',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'content_processor' => ContentProcessorBehavior::className(),
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getFilePath()
    {
        $fileDir = FileUploadForm::generateFileDir();
        $filePath = $fileDir . DIRECTORY_SEPARATOR . $this->file_name . '.' . $this->file_extension;

        return $filePath;
    }

    /**
     * @return Query
     */
    public function getStats()
    {
        return $this->hasMany(FileUsageStatModel::className(), ['uploaded_file_id' => 'id'])->orderBy(['usage_date' => SORT_ASC]);
    }
}
