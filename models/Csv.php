<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This is the model dinamic class for "Csv".
 * @property
 */
class Csv extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $csv_file;

    public function rules()
    {
        return [
            [['csv_file'], 'file', //'extensions' => 'csv,xlsx,xls',
                'mimeTypes' => [
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'text/csv'
                ],
                'wrongMimeType' => \Yii::t('app', 'Only csv,xlsx,xls files are allowed.'),
                'checkExtensionByMimeType' => true,
                'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return ['csv_file' => 'Upload CSV File'];
    }
}

