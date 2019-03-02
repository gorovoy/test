<?php

use yii\db\Migration;

/**
 * Class m190118_115210_create_patient
 */
class m190118_115210_create_patient extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%patient}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'doctor_id' => $this->integer()->notNull(),
            'capitation' => $this->smallInteger(),
            'capitation_start' => $this->date(),
            'capitation_end' => $this->date(),
        ], $tableOptions);

        $this->createIndex('idx-patient-id', '{{%patient}}', 'id');

        $this->addForeignKey('fk-patient-doctor_id', '{{%patient}}', 'doctor_id', '{{%doctor}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%patient}}');
    }
}
