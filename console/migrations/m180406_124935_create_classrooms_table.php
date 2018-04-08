<?php

use yii\db\Migration;

/**
 * Handles the creation of table `classrooms`.
 */
class m180406_124935_create_classrooms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('classrooms', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('classrooms');
    }
}
