<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


class UploadFiles extends ActiveRecord
{

    public static function tableName() {
        return 'UploadFiles';
    }


}