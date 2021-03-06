<?php

use yii\db\Migration;

/**
 * Handles the creation of table `homeworks`.
 * Has foreign keys to the tables:
 *
 * - `lessons`
 * - `students`
 */
class m180408_175507_create_homeworks_table extends Migration
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
        
        $this->createTable('homeworks', [
            'id' => $this->primaryKey(),
            'lesson_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'mark' => $this->integer(),
            'date' => $this->date()->notNull(),
        ], $tableOptions);

        // creates index for column `lesson_id`
        $this->createIndex(
            'idx-homeworks-lesson_id',
            'homeworks',
            'lesson_id'
        );

        // add foreign key for table `lessons`
        $this->addForeignKey(
            'fk-homeworks-lesson_id',
            'homeworks',
            'lesson_id',
            'lessons',
            'id',
            'CASCADE'
        );

        // creates index for column `student_id`
        $this->createIndex(
            'idx-homeworks-student_id',
            'homeworks',
            'student_id'
        );

        // add foreign key for table `students`
        $this->addForeignKey(
            'fk-homeworks-student_id',
            'homeworks',
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
        // drops foreign key for table `lessons`
        $this->dropForeignKey(
            'fk-homeworks-lesson_id',
            'homeworks'
        );

        // drops index for column `lesson_id`
        $this->dropIndex(
            'idx-homeworks-lesson_id',
            'homeworks'
        );

        // drops foreign key for table `students`
        $this->dropForeignKey(
            'fk-homeworks-student_id',
            'homeworks'
        );

        // drops index for column `student_id`
        $this->dropIndex(
            'idx-homeworks-student_id',
            'homeworks'
        );

        $this->dropTable('homeworks');
    }
}
