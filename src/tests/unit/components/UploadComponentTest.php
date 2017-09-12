<?php


namespace tests\unit\components;
use app\components\UploadComponent;
use app\models\UploadFiles;


class UploadComponentTest  extends \Codeception\Test\Unit {

    public $path;
    
    protected function setUp()
    {
        $this->UploadComponent = new UploadComponent();
    }

    protected function tearDown()
    {
        $this->UploadComponent = NULL;
    }

   private function GetPrivateMethod($method,$params=NULL){

       $class = new \ReflectionClass('app\components\UploadComponent');
       $testMethod = $class->getMethod($method);
       $testMethod->setAccessible(true);
       $obj = new UploadComponent();
       return  $result = $testMethod->invoke($obj,$params);

   }

    public function testSaveFile(){

        $model = new UploadFiles([
            'file_name' => 'TestFile',
            'file_size' => '1000',
            'file_extension'=>'txt',
            'date_create'=>'2017-09-09 16:22:33'
        ]);
        $transaction= \Yii::$app->db->beginTransaction();

        if(expect('model is saved',  $model->save())->true()){
            $transaction->rollBack();
        }
        
    }
    
    public function testGetSumSize(){


        $testArray=['0'=>(object)
                            ["name"=> "test.php",
                            "tempName"=> "/tmp/phpWR3nfB",
                            "type"=> "application/x-php",
                            "size"=> 200,
                            "error"=> 0],
                    '1'=>(object)
                            ["name"=> "testing.js",
                            "tempName"=> "/tmp/php0JoFBF",
                            "type"=> "application/x-javascript",
                            "size"=> 100,
                            "error"=> 0]

                ];
        $result= $this->GetPrivateMethod('GetSumsize',$testArray);
        $this->assertEquals(300, $result);

    }

    public function testValidateDir(){

        $this->path = __DIR__ . "/uploads";

        if (!file_exists($this->path)) {
            mkdir($this->path);
            chmod($this->path, 0777);
        }


        expect('model is saved', $this->GetPrivateMethod('ValidateDir',$this->path));
        rmdir($this->path);
    }



}
