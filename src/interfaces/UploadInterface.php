<?php

namespace app\components\uploadFileComponent;

use app\components\uploadFileComponent\uploadFileException\UploadFileException;

/**
 * Interface UploadFileInterface
 * @package app\components\uploadFileComponent
 */
interface UploadFileInterface
{
    /**
     * Save files in server.
     *
     * @param array $files
     *
     * @throws UploadFileException
     * @return void
     */
    public function uploadFiles(array $files):void;

    /**
     * Save files in database.
     *
     * @param  array $files downloading files
     *
     * @throws UploadFileException
     * @return void
     */
    public function saveFileToDB(array $files):void;
}
