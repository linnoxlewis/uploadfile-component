<?php

namespace app\components\uploadFileComponent;
use app\components\uploadFileComponent\uploadFileException\UploadFileException;
use app\components\uploadFileComponent\UploadFileInterface;
use app\components\uploadFileComponent\models\UploadFiles;
use Yii;

/**
 * Class UploadComponent
 * @package app\components\uploadFileComponent
 */
class UploadService implements UploadFileInterface
{
    /**
     * Save files in server.
     *
     * @param array $files
     *
     * @throws UploadFileException
     * @return void
     */
    public function uploadFiles(array $files): void
    {
        foreach ($files as $file)
        {
           if(!$file->saveAs(Yii::$app->components['upload']['uploadPath'] . $file->baseName . '.' . $file->extension))
           {
               throw new UploadFileException("Не удалось сохранить файл");
           }
        }
    }

    /**
     * Save files in database.
     *
     * @param  array $files downloading files
     *
     * @throws UploadFileException
     * @return void
     */
    public function saveFileToDB(array $files) : void
    {
        foreach ($files as $file) {

            $fileModel = new UploadFiles([
                'name' => $file->baseName,
                'size' => $file->size,
                'create_at' => date("Y-m-d H:i:s"),
                'extension' => $file->extension,
                'path' => Yii::$app->components['upload']['uploadPath'],
            ]);

            if(!$fileModel->save()){
                throw new UploadFileException("Не удалось сохранить данные");
            }
        }
    }
}