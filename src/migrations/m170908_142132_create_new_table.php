<?php

use yii\db\Migration;

/**
 * Handles the creation of table `new`.
 */
class m170908_142132_create_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {


        $this->createTable('UploadFiles',[
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'size' => 'integer',
            'extension' => 'string',
            'create_at' => 'datetime',
            'path' => 'string'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('UploadFiles');
    }
}
