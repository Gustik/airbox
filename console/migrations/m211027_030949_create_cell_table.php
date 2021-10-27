<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cell}}`.
 */
class m211027_030949_create_cell_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%cell}}', [
            'id' => $this->char(36)->notNull(),
            'name' => $this->string(),
            'address' => $this->string(),
            'status' => $this->integer(),
            'baggageId' => $this->integer()->null(),
            'startDate' => $this->dateTime()->null(),
            'daysCount' => $this->integer(),
            'price' => $this->integer(),
            'pinCode' => $this->string(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-cell', '{{%cell}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cell}}');
    }
}
