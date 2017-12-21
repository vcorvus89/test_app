<?php

namespace app\components;

use yii\base\Component;

/**
 * Class CsvContentProcessor
 * @package app\components
 */
class CsvContentProcessor extends Component
{
    /**
     * @param $model
     * @return array
     * @throws \Exception
     */
    public function getFileContent($model)
    {
        $fileContent = [];
        $row = 1;
        if (($handle = fopen($model->getFilePath(), "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if (count($data) != 3) {
                    throw new \Exception('Wrong column count');
                }

                if ($row > 1) {
                    $fileContent[] = $data;
                }
                ++$row;
            }

            fclose($handle);
        }

        return $fileContent;
    }
}