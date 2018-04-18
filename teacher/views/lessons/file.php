<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>

<a href="/uploads/lessons/<?= "$model->id." . pathinfo($model->file, PATHINFO_EXTENSION) ?>" download="<?= $model->file ?>"><?= $model->file ?> <i class="glyphicon glyphicon-download"></i></a>
<?php if($model->file) { 
    $form = ActiveForm::begin(['action' => ['remove', 'id' => $model->id], 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <a onclick='this.parentNode.submit(); return false;'><i class="glyphicon glyphicon-remove"></i></a>
<?php
ActiveForm::end();
} ?>