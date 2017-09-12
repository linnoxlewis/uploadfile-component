<?php


return [
    'class'=>'app\components\UploadComponent',
    'uploadPath'=>$_SERVER['DOCUMENT_ROOT']."basic/uploads/",
    'maxFileSize'=> 2000,
    'maxAllFilesSize'=>3000,
    'maxCountFiles'=>3,
    'extension'=>['php','css','js','png']
];
