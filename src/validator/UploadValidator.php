<?php

namespace app\components\uploadFileComponent\validator;

use Yii;
use app\components\uploadFileComponent\uploadFileException\UploadFileException;

/**
 * Class UploadValidator
 * @package app\components\uploadFileComponent\validator
 */
class UploadValidator
{
    /**
     * path to file.
     *
     * @var string
     */
    public $uploadPath;
    /**
     * Max file size.
     *
     * @var int
     */
    public $maxFileSize;
    /**
     * Max all file size.
     *
     * @var int
     */
    public $maxAllFilesSize;
    /**
     * Max count size.
     *
     * @var int
     */
    public $maxCountFiles;
    /**
     * extensions of files.
     *
     * @var array
     */
    public $extension;

    /**
     * $__FIles
     *
     * @var array
     */
    public $files;

    public function __construct()
    {
        $this->maxFileSize = Yii::$app->components['upload']['maxFileSize'];
        $this->uploadPath = Yii::$app->components['upload']['uploadPath'];
        $this->maxAllFilesSize = Yii::$app->components['upload']['maxAllFilesSize'];
        $this->maxCountFiles = Yii::$app->components['upload']['maxCountFiles'];
        $this->extension = Yii::$app->components['upload']['extension'];
    }

    /**
     * Validate file params;
     *
     * @throws \Exception
     * @return bool
     */
    public function validate()
    {
        if ($this->validatePath()) {
            if ($this->getSumSize($this->files) > $this->maxAllFilesSize) {
                throw new UploadFileException("Суммарный размер всех файлов превышает " . $this->maxAllFilesSize . " кб", 500);
            }
            if (count($this->files) > $this->maxCountFiles) {
                throw new UploadFileException("Превышен лимит количества одновременно загружаемых файлов ", 500);
            }
            foreach ($this->files as $file) {
                if (!in_array($file->extension, $this->extension)) {
                    throw new UploadFileException("Файл " . $file->baseName . " имеет недопустимое расширение", 500);
                }
                if ($file->size > $this->maxFileSize) {
                    throw new UploadFileException("Размер Файла " . $file->baseName . " превышает допустимиый размер", 500);
                }
            }
        }
        return true;
    }

    /**
     * Method calculate the sum of the file sizes
     *
     * @param array $files uploadable files
     *
     * @return integer
     */
    private function getSumSize(array $files): int
    {
        $sum = [];
        foreach ($files as $file) {
            $sum[] = $file->size;
        }
        $sum = array_sum($sum);

        return $sum;
    }

    /**
     * Validate directory
     *
     * @throws UploadFileException
     * @return bool
     */
    private function validatePath(): bool
    {
        if (!(file_exists($this->uploadPath) & is_dir($this->uploadPath))) {
            throw new UploadFileException("Директория " . $this->uploadPath . " не существует или не является директорией");
        }
        if (!is_writable($this->uploadPath)) {
            throw new UploadFileException("Директория " . $this->uploadPath . " не обладает правами на запись");
        }
        return true;
    }
}
