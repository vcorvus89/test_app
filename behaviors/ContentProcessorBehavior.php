<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use app\components\CsvContentProcessor;
use app\models\FileUsageStatModel;

/**
 * Class ContentProcessorBehavior
 * @package app\behaviors
 */
class ContentProcessorBehavior extends Behavior
{
    /**
     * @var null|CsvContentProcessor
     */
    public $contentProcessor = null;

    /**
     * @var array
     */
    private $contentDataModels = [];

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }

    /**
     * @param $event
     * @return bool
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeInsert($event)
    {
        $this->setContentProcessor();

        $data = $this->contentProcessor->getFileContent($this->owner);

        foreach ($data as $row) {
            $model = new FileUsageStatModel();
            $model->scenario = FileUsageStatModel::SCENARIO_VALIDATE;
            $model->usage_date = $row[0];
            $model->event_type = $row[1];
            $model->file_name = $row[2];

            if (!$model->validate()) {
                @unlink($this->owner->getFilePath());

                throw new \Exception(sprintf('Row with data "%s" "%s" "%s" is invalid', $row[0], $row[1], $row[2]));
            }

            $this->contentDataModels[] = $model;
        }

        return true;
    }

    /**
     * @param $event
     * @throws \Exception
     */
    public function afterInsert($event)
    {
        $data = [];

        foreach ($this->contentDataModels as $model) {
            $data[] = [$this->owner->id, $model->usage_date, $model->event_type, $model->file_name];
        }

        Yii::$app->db->createCommand()->batchInsert(
            FileUsageStatModel::tableName(),
            ['uploaded_file_id', 'usage_date', 'event_type', 'file_name'],
            $data
        )->execute();
    }

    /**
     * @return array
     */
    public function getTableData()
    {
        $data = [];

        $models = $this->owner->stats;

        /** @var FileUsageStatModel $model */
        foreach ($models as $model) {
            $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $model->usage_date);

            $date = $dateTime->format('Y-m-d');
            $dateKey = strtotime($date);

            if (!array_key_exists($dateKey, $data)) {
                $data[$dateKey] = [
                    'date' => $date,
                    'upload_cnt' => 0,
                    'download_cnt' => 0,
                    'top_file_download' => [
                        'filename' => '',
                        'download_cnt' => 0,
                    ],
                    'files_download_cnt' => [],
                ];
            }

            if ($model->event_type == 'file_download') {
                ++$data[$dateKey]['download_cnt'];

                if (!array_key_exists($model->file_name, $data[$dateKey]['files_download_cnt'])) {
                    $data[$dateKey]['files_download_cnt'][$model->file_name] = 0;
                }

                ++$data[$dateKey]['files_download_cnt'][$model->file_name];

                if ($data[$dateKey]['top_file_download']['filename'] == $model->file_name) {
                    $data[$dateKey]['top_file_download']['download_cnt'] = $data[$dateKey]['files_download_cnt'][$model->file_name];
                } elseif (!$data[$dateKey]['top_file_download']['filename'] || ($data[$dateKey]['files_download_cnt'][$model->file_name] > $data[$dateKey]['top_file_download']['download_cnt'])) {
                    $data[$dateKey]['top_file_download']['filename'] = $model->file_name;
                    $data[$dateKey]['top_file_download']['download_cnt'] = $data[$dateKey]['files_download_cnt'][$model->file_name];
                }
            }

            if ($model->event_type == 'file_upload') {
                ++$data[$dateKey]['upload_cnt'];
            }
        }

        ksort($data);

        return $data;
    }

    /**
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    private function setContentProcessor()
    {
        switch ($this->owner->file_extension) {
            case 'csv':
                $this->contentProcessor = Yii::createObject(['class' => CsvContentProcessor::className()]);
                break;
            default:
                throw new \Exception(sprintf('Content processor for file with extension "%s" not found', $this->file_extension));
                break;
        }
    }
}