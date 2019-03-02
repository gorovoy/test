<?php

use yii\db\Migration;

/**
 * Class m190118_115658_create_payment
 */
class m190118_115658_create_payment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'denial_reason' => $this->string(),
            'date' => $this->date()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-payment-id', '{{%payment}}', 'id');

        $this->addForeignKey('fk-payment-patient_id', '{{%payment}}', 'patient_id', '{{%patient}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%payment}}');
    }
}
