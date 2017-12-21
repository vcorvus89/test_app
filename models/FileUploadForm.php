<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\UploadedFileModel;

/**
 * Class FileUploadForm
 * @package app\models
 */
class FileUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var string
     */
    public static $baseDir = 'uploads';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                ['file'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => 'csv',
                'checkExtensionByMimeType' => false
            ],
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function upload()
    {
        if ($this->validate()) {
            $fileName = $this->generateFileName();
            $filePath = self::generateFileDir() . DIRECTORY_SEPARATOR . $fileName . '.' . $this->file->extension;

            $uploadedFileModel = new UploadedFileModel();
            $uploadedFileModel->file_name = $fileName;
            $uploadedFileModel->file_extension = $this->file->extension;

            if (!$uploadedFileModel->validate()) {
                return false;
            }

            if ($this->file->saveAs($filePath)) {
                $uploadedFileModel->save();
            }

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    private function generateFileName()
    {
        return uniqid();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateFileDir()
    {
        $dir = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . self::$baseDir;

        if (!is_dir($dir)) {
            if (!@mkdir($dir)) {
                throw new \Exception(sprintf('Failed to create directory "%s"', self::$baseDir));
            }
        }

        return $dir;
    }
}