<?php

use yii\db\Schema;
use yii\db\Migration;

class m160810_124729_add_users extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id'             => Schema::TYPE_PK,
            'username'          => Schema::TYPE_STRING,
            'email'          => Schema::TYPE_STRING,
            'password'       => Schema::TYPE_STRING,
            'created_at'     => Schema::TYPE_DATETIME . ' NOT NULL',
            'updated_at'     => Schema::TYPE_DATETIME . ' NOT NULL',
        ]);
    }

    public function down()
    {
        echo "m160810_124729_add_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
