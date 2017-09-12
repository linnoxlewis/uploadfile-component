<?php

use yii\db\Migration;

/**
 * Handles the creation of table `new`.
 */
class m170908_142132_create_new_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        

	$this->createTable('UploadFiles', array(
		    'file_id'           => 'pk',
		    'file_name'      	=> 'string NOT NULL',
		    'file_size' 	=> 'integer',
		    'file_extension'    => 'string',
		    'date_create'       => 'datetime',
                    'file_path'         =>'string'
		    
		));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('UploadFiles');
    }
}
