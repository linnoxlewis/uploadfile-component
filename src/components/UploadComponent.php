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
     * Inicialization component.
     *
     * 
     */
    public function init()
    {
        parent::init();

    }


    /**
     * Method validate directory for uploading
     * @param string path to directory for uploading
     * @return boolean
     */
    private function ValidateDir($path=NULL){

        if ($path==NULL) $path=$this->uploadPath;

        if(!(file_exists($path) & is_dir($path))){
            throw new Exception("Директории " . $this->uploadPath . "Не существует или не является директорией",500);
        }
        elseif (!is_writable($path)){
            throw new Exception("У вас нет прав для записи в эту директорию:" . $path,500);
            return false;
        }

        else {

            return true;
        }


    }

    /**
     * Method calculate the sum of the file sizes
     * @param object uploadable files
     * @return integer
     */
    private function GetSumSize($files){

        $sum=array();
        $files = (array) $files;
        foreach ($files as $file) {
            $sum[]=$file->size;
        }

        $sum = array_sum($sum);

        return $sum;
    }

    /**
     * method inserts information into the database
     * @param object uploadable files
     * 
     */
    public function SaveFile($file){
        $model = new UploadFiles();

        $model->file_name = $file->baseName;
        $model->file_size =$file->size;
        $model->date_create=date("Y-m-d H:i:s");
        $model->file_extension = $file->extension;
        $model->file_path = $this->uploadPath;
        $model->save();
    }


    /**
     * method downloads files to the server
     * @param object uploadable files
     * 
     */
    public function Upload($files)
    {
        $files = UploadedFile::getInstances($files, 'file');

        if ($files) {

            $this->ValidateDir();

            if ($this->GetSumSize($files) > $this->maxAllFilesSize) throw new Exception("Суммарный размер всех файлов превышает " . $this->maxAllFilesSize . " кб" , 500);
            elseif (count($files) > $this->maxCountFiles) throw new Exception("Превышен лимит количества одновременно загружаемых файлов ",500);

            foreach ($files as $file) {

                if (!in_array($file->extension, $this->extension)) {
                    throw new Exception( "Файл " . $file->baseName . " имеет недопустимое расширение",500);
                }
                elseif ($file->size > $this->maxFileSize) {
                    throw new Exception("Размер Файла " . $file->baseName . " превышает допустимиый размер",500);
                }
                elseif ($file->saveAs($this->uploadPath . $file->baseName . '.' . $file->extension)) {
                    $this->savefile($file);
                }

            }

        }

        else{

            throw new Exception('Не выбраны файлы для отправки', 500);

        }
    }



}

