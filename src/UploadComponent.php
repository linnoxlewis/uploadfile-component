<?php

namespace app\components\uploadFileComponent;

use app\components\uploadFileComponent\uploadFileException\UploadFileException;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\base\Component;
use app\components\uploadFileComponent\validator\UploadValidator;

/**
 * Class UploadComponent
 * @package app\components
 */
Class UploadComponent extends Component
{
    /**
     * UploadFile object
     *
     * @var UploadFileInterface
     */
    public $uploadFile;

    public function __construct(UploadFileInterface $uploadFile, array $config = [])
    {
        $this->uploadFile = $uploadFile;

        parent::__construct($config);
    }

    /**
     * Method downloads files to the server and save to dataBase.
     *
     * @param Model $files downloading files.
     *
     * @return void
     */
    public function Upload($files): void
    {
        $files = UploadedFile::getInstances($files, 'file');

        $validator = new UploadValidator();
        $validator->files = $files;
        $validator->validate();

        try {
            $this->uploadFile->uploadFiles($files);
            $this->uploadFile->saveFileToDB($files);
        } catch (UploadFileException $exception) {
            $exception->getMessage();
        }
    }
}
