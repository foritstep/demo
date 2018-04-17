<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property int $group_id
 * @property string $name
 * @property string $email
 * @property string $password
 *
 * @property Homeworks[] $homeworks
 * @property Groups $group
 */
class Students extends \yii\db\ActiveRecord
{
    public $password;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'students';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'name', 'email', 'password'], 'required'],
            [['group_id'], 'integer'],
            [['name', 'email', 'password'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_id' => 'id']],
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
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworks()
    {
        return $this->hasMany(Homeworks::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'group_id']);
    }
}
