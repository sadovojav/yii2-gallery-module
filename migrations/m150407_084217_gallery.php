<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150407_084217_gallery
 */
class m150407_084217_gallery extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        /**
         * Create {{%gallery}} table
         */
        $this->createTable('{{%gallery}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(50) NOT NULL',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'created' => Schema::TYPE_DATETIME . ' NOT NULL',
            'updated' => Schema::TYPE_DATETIME . ' NOT NULL',
        ], $tableOptions);

        $this->createIndex('idx_gallery_id_status', '{{%gallery}}', 'id, status');

        /**
         * Create {{%gallery_file}} table
         */
        $this->createTable('{{%gallery_file}}', [
            'id' => Schema::TYPE_PK,
            'galleryId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'file' => Schema::TYPE_STRING . '(255) NOT NULL',
            'caption' => Schema::TYPE_STRING . '(255)',
            'position' => Schema::TYPE_INTEGER . '(1) DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_gallery_galleryId', '{{%gallery_file}}', 'galleryId');
        $this->addForeignKey('fk_gallery_file_galleryId', '{{%gallery_file}}', 'galleryId', '{{%gallery}}',
            'id','CASCADE','RESTRICT');
    }

    public function down()
    {
        echo "m150407_084217_gallery cannot be reverted.\n";

        $this->dropTable('{{%gallery}}');
        $this->dropTable('{{%gallery_file}}');

        return false;
    }
}
