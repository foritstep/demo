<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "homeworks".
 *
 * @property int $id
 * @property int $lesson_id
 * @property int $student_id
 * @property string $file
 * @property string $mark
 * @property string $date
 *
 * @property Lessons $lesson
 * @property Students $student
 */
class Homeworks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'homeworks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lesson_id', 'student_id', 'file', 'mark', 'date'], 'required'],
            [['lesson_id', 'student_id'], 'integer'],
            [['date'], 'safe'],
            [['file', 'mark'], 'string', 'max' => 255],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lessons::className(), 'targetAttribute' => ['lesson_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Students::className(), 'targetAttribute' => ['student_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_id' => 'Lesson ID',
            'student_id' => 'Student ID',
            'file' => 'File',
            'mark' => 'Mark',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLesson()
    {
        return $this->hasOne(Lessons::className(), ['id' => 'lesson_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Students::className(), ['id' => 'student_id']);
    }
}
