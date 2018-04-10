<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedules".
 *
 * @property int $id
 * @property int $course_id
 * @property int $number
 * @property int $day
 * @property int $classroom_id
 *
 * @property Classrooms $classroom
 * @property Courses $course
 */
class Schedules extends \yii\db\ActiveRecord
{
    public $count;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'number', 'day', 'classroom_id'], 'required'],
            [['course_id', 'number', 'day', 'classroom_id'], 'integer'],
            [['classroom_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classrooms::className(), 'targetAttribute' => ['classroom_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Courses::className(), 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'number' => 'Number',
            'day' => 'Day',
            'classroom_id' => 'Classroom ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassroom()
    {
        return $this->hasOne(Classrooms::className(), ['id' => 'classroom_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Courses::className(), ['id' => 'course_id']);
    }
}
