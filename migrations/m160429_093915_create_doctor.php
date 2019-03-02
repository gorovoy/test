<?php

use yii\db\Migration;

class m160429_093915_create_doctor extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%doctor}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-doctor-id', '{{%doctor}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%doctor}}');
    }
}
