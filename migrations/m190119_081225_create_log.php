<?php

use yii\db\Migration;

/**
 * Class m190119_081225_create_log
 */
class m190119_081225_create_log extends Migration
{
    public function up()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'message' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%log}}');
    }
}
