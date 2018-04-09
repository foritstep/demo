<?php

use yii\db\Migration;

/**
 * Handles the creation of table `schedules`.
 * Has foreign keys to the tables:
 *
 * - `courses`
 * - `classrooms`
 */
class m180408_174919_create_schedules_table extends Migration
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
        
        $this->createTable('schedules', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'number' => $this->integer()->notNull(),
            'day' => $this->integer()->notNull(),
            'classroom_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `course_id`
        $this->createIndex(
            'idx-schedules-course_id',
            'schedules',
            'course_id'
        );

        // add foreign key for table `courses`
        $this->addForeignKey(
            'fk-schedules-course_id',
            'schedules',
            'course_id',
            'courses',
            'id',
            'CASCADE'
        );

        // creates index for column `classroom_id`
        $this->createIndex(
            'idx-schedules-classroom_id',
            'schedules',
            'classroom_id'
        );

        // add foreign key for table `classrooms`
        $this->addForeignKey(
            'fk-schedules-classroom_id',
            'schedules',
            'classroom_id',
            'classrooms',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `courses`
        $this->dropForeignKey(
            'fk-schedules-course_id',
            'schedules'
        );

        // drops index for column `course_id`
        $this->dropIndex(
            'idx-schedules-course_id',
            'schedules'
        );

        // drops foreign key for table `classrooms`
        $this->dropForeignKey(
            'fk-schedules-classroom_id',
            'schedules'
        );

        // drops index for column `classroom_id`
        $this->dropIndex(
            'idx-schedules-classroom_id',
            'schedules'
        );

        $this->dropTable('schedules');
    }
}
