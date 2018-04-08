<?php

use yii\db\Migration;

/**
 * Handles the creation of table `students`.
 * Has foreign keys to the tables:
 *
 * - `groups`
 */
class m180408_174835_create_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('students', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
        ]);

        // creates index for column `group_id`
        $this->createIndex(
            'idx-students-group_id',
            'students',
            'group_id'
        );

        // add foreign key for table `groups`
        $this->addForeignKey(
            'fk-students-group_id',
            'students',
            'group_id',
            'groups',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `groups`
        $this->dropForeignKey(
            'fk-students-group_id',
            'students'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            'idx-students-group_id',
            'students'
        );

        $this->dropTable('students');
    }
}
