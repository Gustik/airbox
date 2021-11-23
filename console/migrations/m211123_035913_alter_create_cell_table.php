<?php

use yii\db\Migration;

/**
 * Class m211123_035913_alter_create_cell_table
 */
class m211123_035913_alter_create_cell_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%cell}}', 'baggageId', $this->char(36)->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211123_035913_alter_create_cell_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211123_035913_alter_create_cell_table cannot be reverted.\n";

        return false;
    }
    */
}
