<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function upload($lesson, $user)
    {
        if ($this->validate()) {
            $this->file->saveAs("../../uploads/homeworks/$lesson-$user." . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}
