<?php

use yii\db\Migration;

/**
 * Handles the creation of table `courses`.
 * Has foreign keys to the tables:
 *
 * - `groups`
 * - `teachers`
 */
class m180408_174822_create_courses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('courses', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'teacher_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'begin' => $this->date()->notNull(),
        ], $tableOptions);

        // creates index for column `group_id`
        $this->createIndex(
            'idx-courses-group_id',
            'courses',
            'group_id'
        );

        // add foreign key for table `groups`
        $this->addForeignKey(
            'fk-courses-group_id',
            'courses',
            'group_id',
            'groups',
            'id',
            'CASCADE'
        );

        // creates index for column `teacher_id`
        $this->createIndex(
            'idx-courses-teacher_id',
            'courses',
            'teacher_id'
        );

        // add foreign key for table `teachers`
        $this->addForeignKey(
            'fk-courses-teacher_id',
            'courses',
            'teacher_id',
            'teachers',
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
            'fk-courses-group_id',
            'courses'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            'idx-courses-group_id',
            'courses'
        );

        // drops foreign key for table `teachers`
        $this->dropForeignKey(
            'fk-courses-teacher_id',
            'courses'
        );

        // drops index for column `teacher_id`
        $this->dropIndex(
            'idx-courses-teacher_id',
            'courses'
        );

        $this->dropTable('courses');
    }
}
