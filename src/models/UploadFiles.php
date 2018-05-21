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

    public function rules()

    {
        return [
            [
                [
                    'name',
                    'size',
                    'extension',
                    'create_at',
                    'path',
                ],
                'required',
            ],
            ['path', 'validatePath'],
        ];
    }

    /**
     * Method validate directory for uploading
     * @param string $attribute атрибут проверяемый в настоящее время
     * @param array $params дополнительные пары имя-значение, заданное в правиле
     *
     */
    public function validatePath($attribute, $params){

        if(!(file_exists($this->attribute) & is_dir($this->attribute))){
            $this->addError($attribute,"Директории не существует или она не является директорией");

        }
        if (!is_writable($this->attribute)){
            $this->addError($attribute,"У вас нет прав для записи в эту директорию");
        }
    }
}