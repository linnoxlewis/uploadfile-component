<?php
namespace app\components\uploadFileComponent\models;

use yii\db\ActiveRecord;

/**
 * Class UploadFiles
 * @package app\components\uploadFileComponent\models
 */
class UploadFiles extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'UploadFiles';
    }
}
