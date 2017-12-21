<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_file_usage_stat".
 *
 * @property integer $id
 * @property integer $uploaded_file_id
 * @property string $usage_date
 * @property string $event_type
 * @property string $file_name
 *
 * @property AppUploadedFile $uploadedFile
 */
class FileUsageStatModel extends \yii\db\ActiveRecord
{
    const SCENARIO_VALIDATE = 'validate';
    const SCENARIO_SAVE = 'save';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_file_usage_stat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usage_date', 'event_type', 'file_name'], 'required', 'on' => [self::SCENARIO_VALIDATE]],
            [['uploaded_file_id', 'usage_date', 'event_type', 'file_name'], 'required', 'on' => [self::SCENARIO_SAVE]],
            [['uploaded_file_id'], 'integer', 'on' => [self::SCENARIO_SAVE]],
            [['usage_date'], 'safe'],
            [['event_type'], 'string', 'max' => 20],
            [['file_name'], 'string', 'max' => 255],
            ['usage_date', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uploaded_file_id' => 'Uploaded File ID',
            'usage_date' => 'Usage Date',
            'event_type' => 'Event Type',
            'file_name' => 'Filename',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedFile()
    {
        return $this->hasOne(AppUploadedFile::className(), ['id' => 'uploaded_file_id']);
    }
}
