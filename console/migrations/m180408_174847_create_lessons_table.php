<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lessons`.
 * Has foreign keys to the tables:
 *
 * - `courses`
 */
class m180408_174847_create_lessons_table extends Migration
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
        
        $this->createTable('lessons', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'theme' => $this->string()->notNull(),
            'homework' => $this->string()->notNull(),
            'file' => $this->string(),
            'date' => $this->date()->notNull(),
        ], $tableOptions);

        // creates index for column `course_id`
        $this->createIndex(
            'idx-lessons-course_id',
            'lessons',
            'course_id'
        );

        // add foreign key for table `courses`
        $this->addForeignKey(
            'fk-lessons-course_id',
            'lessons',
            'course_id',
            'courses',
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
            'fk-lessons-course_id',
            'lessons'
        );

        // drops index for column `course_id`
        $this->dropIndex(
            'idx-lessons-course_id',
            'lessons'
        );

        $this->dropTable('lessons');
    }
}
