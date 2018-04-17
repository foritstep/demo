<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lessons".
 *
 * @property int $id
 * @property int $course_id
 * @property string $theme
 * @property string $homework
 * @property string $file
 * @property string $date
 *
 * @property Homeworks[] $homeworks
 * @property Courses $course
 */
class Lessons extends \yii\db\ActiveRecord
{
    public $h_id, $mark, $h_file, $h_date;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lessons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'theme', 'homework', 'date'], 'required'],
            [['course_id'], 'integer'],
            [['date'], 'safe'],
            [['theme', 'homework', 'file'], 'string', 'max' => 255],
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
            'theme' => 'Theme',
            'homework' => 'Homework',
            'file' => 'File',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworks()
    {
        return $this->hasMany(Homeworks::className(), ['lesson_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Courses::className(), ['id' => 'course_id']);
    }
}
