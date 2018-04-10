<?php

namespace app\models;

use app\models\Schedules;
use Yii;

/**
 * This is the model class for table "courses".
 *
 * @property int $id
 * @property int $group_id
 * @property string $name
 * @property int $teacher_id
 * @property int $quantity
 *
 * @property Groups $group
 * @property Teachers $teacher
 * @property Lessons[] $lessons
 * @property Schedules[] $schedules
 */
class Courses extends \yii\db\ActiveRecord
{
    public function getSchedule()
    {
        $arr = array_fill(0, 16,
            array_fill(0, 8, false)
        );

        foreach($this->getSchedules()->all() as $i) {
            $arr[$i->number - 1][$i->day - 1] = $i->classroom_id;
        }

        return $arr;
    }

    public function setSchedule($val)
    {
        $exist = [];
        $new = [];
        foreach($this->getSchedules()->all() as $i) {
            $exist[] = $i->number . ":" . $i->day . ":" . $i->classroom_id;
        }
        foreach($val as $x => $i) {
            foreach($i as $y => $j) {
                !$j or $new[] = ($x + 1) . ":" . ($y + 1) . ":" . $j;
            }
        }
        foreach(array_diff($new, $exist) as $i) {
            $s = new Schedules();
            $data = explode(':', $i);
            $s->course_id = $this->id;
            $s->number = $data[0];
            $s->day = $data[1];
            $s->classroom_id = $data[2];
            $s->save();
        }
        foreach(array_diff($exist, $new) as $i) {
            $data = explode(':', $i);
            $this->getSchedules()->where([
                'number' => $data[0],
                'day' => $data[1],
                'classroom_id' => $data[2],
            ])->one()->delete();
        }
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'name', 'teacher_id', 'quantity'], 'required'],
            [['group_id', 'teacher_id', 'quantity'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'name' => 'Name',
            'teacher_id' => 'Teacher ID',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessons()
    {
        return $this->hasMany(Lessons::className(), ['course_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedules::className(), ['course_id' => 'id']);
    }
}
