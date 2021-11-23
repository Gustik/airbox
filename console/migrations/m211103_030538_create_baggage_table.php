<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%baggage}}`.
 */
class m211103_030538_create_baggage_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%baggage}}', [
            'id' => $this->char(36)->notNull(),
            'phone' => $this->string(),
            'status' => $this->integer(),
            'date' => $this->dateTime()->null(),

        ], $tableOptions);

        $this->addPrimaryKey('pk-baggage', '{{%baggage}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%baggage}}');
    }
}
