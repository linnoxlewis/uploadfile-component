<?php

namespace app\components;

use yii\base\Exception;
use yii\web\UploadedFile;
use yii;
use yii\base\Component;
use app\models\UploadFiles;

Class UploadComponent extends Component
{
    public $uploadPath;
    public $maxFileSize;
    public $maxAllFilesSize;
    public $maxCountFiles;
    public $extension;


    /**
     * Method calculate the sum of the file sizes
     * @param object uploadable files
     * @return integer
     */
    private function getSumSize($files)
    {

        foreach ($files as $file) {
            $sum[] = $file->size;
        }

        $sum = array_sum($sum);

        return $sum;
    }

    /**
     * method inserts information into the database
     * @param object uploadable files
     *
     */
    private function saveFiles($files)
    {
        foreach ($files as $file) {
            $file->saveAs($this->uploadPath . $file->baseName . '.' . $file->extension);

            $fileModel = new UploadedFiles([
                'name' => $file->baseName,
                'size' => $file->size,
                'create_at' => date("Y-m-d H:i:s"),
                'extension' => $file->extension,
                'path' => $this->uploadPath,
            ]);

            if(!$fileModel->save()){
                throw new Exception("Не удалось сохранить данные")
            }
        }
    }

    private function validateFiles($files)
    {
        if ($this->getSumSize($files) > $this->maxAllFilesSize) {
            throw new Exception("Суммарный размер всех файлов превышает " . $this->maxAllFilesSize . " кб", 500);
        }
        if (count($files) > $this->maxCountFiles) {
            throw new Exception("Превышен лимит количества одновременно загружаемых файлов ", 500);
        }
        foreach ($files as $file) {
            if (!in_array($file->extension, $this->extension)) {
                throw new Exception("Файл " . $file->baseName . " имеет недопустимое расширение", 500);
            }
            if ($file->size > $this->maxFileSize) {
                throw new Exception("Размер Файла " . $file->baseName . " превышает допустимиый размер", 500);
            }
        }
    }

    /**
     * method downloads files to the server
     * @param object uploadable files
     *
     */
    public function Upload($files)
    {
        $files = UploadedFile::getInstances($files, 'file');

        try {
            $this->validateFiles($files);
            $this->saveFiles($files);
        } catch (Exception $exception) {

            return $exception->getMessage();
        }
    }


}

