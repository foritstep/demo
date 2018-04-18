<?php

use yii\db\Migration;

/**
 * Handles the creation of table `exams`.
 * Has foreign keys to the tables:
 *
 * - `courses`
 * - `students`
 */
class m180418_133248_create_exams_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('exams', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
            'mark' => $this->string()->notNull(),
        ]);

        // creates index for column `course_id`
        $this->createIndex(
            'idx-exams-course_id',
            'exams',
            'course_id'
        );

        // add foreign key for table `courses`
        $this->addForeignKey(
            'fk-exams-course_id',
            'exams',
            'course_id',
            'courses',
            'id',
            'CASCADE'
        );

        // creates index for column `student_id`
        $this->createIndex(
            'idx-exams-student_id',
            'exams',
            'student_id'
        );

        // add foreign key for table `students`
        $this->addForeignKey(
            'fk-exams-student_id',
            'exams',
            'student_id',
            'students',
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
            'fk-exams-course_id',
            'exams'
        );

        // drops index for column `course_id`
        $this->dropIndex(
            'idx-exams-course_id',
            'exams'
        );

        // drops foreign key for table `students`
        $this->dropForeignKey(
            'fk-exams-student_id',
            'exams'
        );

        // drops index for column `student_id`
        $this->dropIndex(
            'idx-exams-student_id',
            'exams'
        );

        $this->dropTable('exams');
    }
}
